<?php

namespace vino\saq;

use Symfony\Component\Config\IQueryableConfig;
use SoapClient;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;

class Webservice
{
    const RECORDS_PER_PAGE = 20;
    
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
     * @return array ('wines' => Wine[], 'pages' => int)
     */
    public function searchWinesByKeyword($keyword, $page = 0)
    {
        $products = array();
        $response = $this->getSoapService()->getProduitsParMotCle(array('DataArea' => array('getProduitsParMotCle' => array('arg0' => $this->lang, 'arg1' => $keyword, 'arg2' => $page * self::RECORDS_PER_PAGE, 'arg3' => self::RECORDS_PER_PAGE))));
        if (isset($response->DataArea->getProduitsParMotCleResponse->return->products)) {
            $results = $response->DataArea->getProduitsParMotCleResponse->return->products;
            is_array($results) || $results = array($results);
            foreach ($results as $product) {
                $products[] = Wine::fromSaq($this->lang, $product);
            }
        }
        
        return array('wines' => $products, 'pages' => $response->DataArea->getProduitsParMotCleResponse->return->nbPages);
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
            if (!$wine) {
                throw new InvalidArgumentException(sprintf('Cannot find wine with code: %s', $code));
            }
            $this->entityManager->persist($wine);
            $this->entityManager->flush();
        }
        
        return $wine;
    }
    
    /**
     * Get wine details from a webservice call
     * @param type $code
     * @return Wine Null if not found
     */
    protected function getWineDetails($code)
    {
        $response = $this->getSoapService()->getDetailProduit(array('DataArea' => array('getDetailProduit' => array('arg0' => $this->lang, 'arg1' => $code))));
        if (!$response) {
            return null;
        }
        
        return Wine::fromSaq($this->lang, $response->DataArea->getDetailProduitResponse->return);
    }
    
    /**
     * Get availability on saq.com (by using a call to the website actually)
     * @param string $code
     * @return integer
     */
    public function getOnlineAvailabilityByWineCode($code)
    {
        $contents = @file_get_contents(sprintf('http://www.saq.com/page/fr/saqcom/x/x/%s', $code));
        $quantity = 0;
        $divToFind = 'product-add-to-cart-inventory';
        $tagBefore = '</span>';
        $tagAfter = '</div>';
        $posQuantity = strpos($contents, $divToFind);
        if ($posQuantity) {
            $quantity = trim(substr(
                $contents,
                strpos($contents, $tagBefore, $posQuantity) + strlen($tagBefore),
                strpos($contents, '</div>', $posQuantity) - strpos($contents, $tagBefore, $posQuantity) - strlen($tagBefore)));
        }
        
        return $quantity;
    }
    
    /**
     * @param string $code
     * @param integer $minimumAvailability
     * @return Availability[]
     */
    public function getAvailabilityByWineCode($code, $minimumAvailability = null)
    {
        $minimumQuantity = is_null($minimumAvailability)
            ? $this->config->get('saq.availability.displayMinimum', 1)
            : $minimumAvailability + 1;
        $availabilities = array();
        $response = $this->getSoapService()->getSuccursales(array('DataArea' => array('getSuccursales' => array('arg0' => $this->lang, 'arg1' => $code))));
        if (isset($response->DataArea->getSuccursalesResponse->return) && is_array($response->DataArea->getSuccursalesResponse->return)) {
            foreach ($response->DataArea->getSuccursalesResponse->return as $avail) {
                $quantity = $avail->nbProduit;
                if ($quantity < $minimumQuantity) {
                    continue;
                }
                $availabilities[] = new Availability($avail);
            }
        }
        
        return $availabilities;
    }
    
    /**
     * Generate the file containing (supposedely) all the points of sale. You
     * should give a poroduct code the most widely available.
     * @param string $file
     * @param string $productCode
     * @return integer Number of Pos found
     */
    public function generatePosFile($file, $productCode)
    {
        $contents = array();
        foreach ($this->getAvailabilityByWineCode($productCode) as $availability) {
            /* @var $availability \vino\saq\Availability */
            $pos = $availability->getPos();
            $contents[(int) $pos->getId()] = array(
                'lat' => (float) $pos->getLat(),
                'long' => (float) $pos->getLong(),
                'name' => $pos->__toString(),
                'id' => $pos->getId());
        }
        
        file_put_contents($file, sprintf("var allPos = %s;", json_encode($contents)));
        
        return count($contents);
    }
    
    /**
     * @return SoapClient
     */
    protected function getSoapService()
    {
        $options = $this->config->get('saq.soap.options', array());
        $options['exceptions'] = true;
        $options['cache_wsdl'] = WSDL_CACHE_NONE;
        $prefix = $this->config->get('kernel.urlPrefix');
        $prefix && $prefix = '/' . $prefix;
        return new SoapClient(sprintf('http://%s%s%s', $_SERVER['HTTP_HOST'], $prefix, $this->config->get('saq.soap.url')), $options);
    }
}