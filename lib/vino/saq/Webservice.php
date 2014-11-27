<?php

namespace vino\saq;

use Symfony\Component\Config\IQueryableConfig;
use SoapClient;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use DateTime;

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
     * @var \Doctrine\ORM\EntityManager
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
     * @param EntityManager $entityManager
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
     * @param integer $page
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
     * @return \vino\saq\Wine
     * @throws InvalidArgumentException
     */
    public function getWine($code)
    {
        $wine = $this->entityManager->getRepository('vino\\saq\\Wine')->findOneBy(array('code' => $code, 'lang' => $this->lang));
        if (!$wine || !$wine->getLastUpdate() || ($wine->getLastUpdate() instanceof \DateTime && ($wine->getLastUpdate()->diff(new \DateTime('now'))->days > Wine::MAX_LIFETIME))) {
            if (!$wine) {
                $wine = $this->getWineDetails($code);
                if (!$wine) {
                    throw new InvalidArgumentException(sprintf('Cannot find wine with code: %s', $code));
                }
            } else {
                $wine = $this->updateWineDetails($wine);
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
     * Update wine details from a webservice call
     * @param \vino\saq\Wine $wine
     * @return Wine Null if not found
     */
    protected function updateWineDetails(Wine $wine)
    {
        $response = $this->getSoapService()->getDetailProduit(array('DataArea' => array('getDetailProduit' => array('arg0' => $this->lang, 'arg1' => $wine->getCode()))));
        if (!$response) {
            return null;
        }

        return Wine::updateFromSaq($wine, $this->lang, $response->DataArea->getDetailProduitResponse->return);
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
                $availabilities[] = Availability::fromSaq($avail);
            }
        }
        
        return $availabilities;
    }
    
    /**
     * Update in DB all the points of sale. You should give a product code the
     * most widely available.
     * @param string $productCode
     * @return integer Number of Pos found
     */
    public function updateAllPos($productCode)
    {
        $count = 0;
        foreach ($this->getAvailabilityByWineCode($productCode) as $availability) {
            /* @var $availability \vino\saq\Availability */
            $pos = $availability->getPos();
            $this->entityManager->persist($pos);
            $count++;
        }
        $this->entityManager->flush();
        
        return $count;
    }

    /**
     * Update the arrivals contained inside this file
     * @param string $filePath
     * @param \DateTime $date
     * @param bool $overwrite
     * @return integer Number of imported arrivals
     * @throws InvalidArgumentException
     */
    public function updateArrivals($filePath, DateTime $date, $overwrite = false)
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException(sprinf('File does not exists or not readable: %s', $filePath));
        }
        $splFile = new \SplFileObject($filePath);
        //Pass the header
        $splFile->rewind();
        $splFile->next();
        $splFile->current();
        $lineTrio = array();
        $importedCount = 0;
        while (!$splFile->eof()) {
            $splFile->next();
            $line = trim($splFile->current());
            if (!$line || preg_match('/^,+$/', $line)) {
                continue;
            }

            $lineTrio[] = $line;
            if (count($lineTrio) == 3) {
                $arrival = Arrival::fromCSVLineTrio($date, $lineTrio);
                $lineTrio = array();

                //Filter out non-wines
                if (!preg_match('/^Vin|Mousseux|Porto|Champagne/', $arrival->getRegion()) && (!preg_match('/^Produits du Terroir/', $arrival->getRegion()) && stripos($arrival->getName(), 'vin') === false)) {
                    continue;
                }

                $existingArrival = $this->entityManager->getRepository('vino\\saq\\Arrival')->findOneBy(array('saqCode' => $arrival->getSaqCode(), 'arrivalCode' => $arrival->getArrivalCode()));
                if ($overwrite) {
                    if ($existingArrival) {
                        $this->entityManager->remove($existingArrival);
                    }
                }
                if (!$existingArrival || $overwrite) {
                    $this->entityManager->persist($arrival);
                    $importedCount++;
                }
            }
        }
        $this->entityManager->flush();

        return $importedCount;
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
