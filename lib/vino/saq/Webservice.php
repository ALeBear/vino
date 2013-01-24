<?php

namespace vino\saq;

use Symfony\Component\Config\IQueryableConfig;
use SoapClient;
use Doctrine\ORM\EntityManager;

class Webservice
{
    /**
     * @var Config
     */
    protected $config;
    
    /**
     * @var string 'en' or 'fr'
     */
    protected $lang;
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    
    /**
     * @param IQueryableConfig $config
     * @return \vino\saq\Webservice $this
     */
    public function injectConfig(IQueryableConfig $config)
    {
        $this->config = $config;
        return $this;
    }
    
    /**
     * @param IQueryableConfig $config
     * @return \vino\saq\Webservice $this
     */
    public function injectEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param string $lang
     * @return \vino\saq\Webservice
     */
    public function injectLanguage($lang)
    {
        $this->lang = $lang;
        return $this;
    }
    
    /**
     * @param string $keyword
     * @return Wine[]
     */
    public function searchWinesByKeyword($keyword, $page = 1)
    {
        $products = array();
        $response = $this->getSoapService()->getResultsKeyword2($keyword, $page, $this->lang);
        if (isset($response->nbPages) && $this->decodeCdata($response->nbPages)) {
            foreach ($response->produits as $product) {
                $products[] = Wine::fromSaq($this->lang, $product);
            }
        }
        
        return $products;
    }
    
    /**
     * Get wine details from a DB or webservice call (save results to DB after)
     * @param type $code
     * @return Wine
     */
    public function getWine($code)
    {
        $wine = $this->entityManager->getRepository('vino\\saq\\Wine')->findOneBy(array('code' => $code, 'lang' => $this->lang));
        if (!$wine) {
            $wine = $this->getWineDetails($code);
            $this->entityManager->persist($wine);
            $this->entityManager->flush();
        }
        
        return $wine;
    }
    
    /**
     * Get wine details from a webservice call
     * @param type $code
     * @return Wine
     */
    protected function getWineDetails($code)
    {
        $response = $this->getSoapService()->getProduit2($code, $this->lang);
        return Wine::fromSaq($this->lang, $response);
    }
    
    /**
     * @param string $code
     * @return Availability[]
     */
    public function getAvailabilityByWineCode($code)
    {
        $minimumQuantity = $this->config->get('saq.availability.displayMinimum', 1);
        $availabilities = array();
        $response = $this->getSoapService()->getSuccursales2('', '', '', $code, $this->lang);
        foreach ($response as $avail) {
            if (!is_object($avail)) {
                continue;
            }
            $quantity = self::decodeCdata($avail->quantiteProduit);
            if ($quantity < $minimumQuantity) {
                continue;
            }
            $availabilities[] = new Availability($avail);
        }
        
        return $availabilities;
    }
    
    /**
     * @return SoapClient
     */
    protected function getSoapService()
    {
        $options = $this->config->get('saq.soap.options', array());
        $options['exceptions'] = true;
        return new SoapClient($this->config->get('saq.soap.url'), $options);
    }
    
    /**
     * @param string $cdata
     * @return string
     */
    public static function decodeCdata($cdata)
    {
        return (string) simplexml_load_string('<g>' . $cdata . '</g>');
    }
}