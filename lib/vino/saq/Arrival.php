<?php

namespace vino\saq;

use \InvalidArgumentException;

/**
 * The details of a wine from SAQ
 * @Entity(repositoryClass="vino\saq\ArrivalRepository") @HasLifecycleCallbacks
 */
class Arrival
{
    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     * @param integer
     */
    protected $id;
    
    /**
     * @Column(type="string", length=15)
     * @var string
     */
    protected $arrivalCode;

    /**
     * @Column(type="date")
     * @var string
     */
    protected $arrivalDate;

    /**
     * @Column(type="string", length=50)
     * @var string
     */
    protected $country;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    protected $region;

    /**
     * @Column(type="string", length=15)
     * @var string
     */
    protected $color;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    protected $name;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    protected $producer;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    protected $importer;

    /**
     * @Column(type="string", length=4)
     * @var string
     */
    protected $vintage;

    /**
     * @Column(type="string", length=15)
     * @var string
     */
    protected $saqCode;

    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $milliliters;

    /**
     * @Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    protected $price;


    /**
     * @param \DateTime $arrivalDate
     * @param string[] $lines
     * @return Arrival
     * @throws InvalidArgumentException
     */
    public static function fromCSVLineTrio(\DateTime $arrivalDate, array $lines)
    {
        $arrival = new self();

        if (count($lines) != 3) {
            throw new \InvalidArgumentException(sprintf('Invalid number of lines: %s', count($lines)));
        }

        $line0Data = explode(',', substr($lines[0], 0, strpos($lines[0], '"')));
        $line2Data = explode(',', substr($lines[2], strpos($lines[2], '"') + 2));
        $arrival->arrivalCode = $line0Data[0];
        $arrival->arrivalDate = $arrivalDate;
        $arrival->country = iconv('iso-8859-1', 'UTF-8//TRANSLIT', $line0Data[1]);
        $arrival->region = iconv('iso-8859-1', 'UTF-8//TRANSLIT', $line0Data[2]);
        $arrival->color = iconv('iso-8859-1', 'UTF-8//TRANSLIT', $line0Data[3]);
        $arrival->name = iconv('iso-8859-1', 'UTF-8//TRANSLIT', trim(substr($lines[0], strpos($lines[0], '"') + 1)));
        $arrival->producer = iconv('iso-8859-1', 'UTF-8//TRANSLIT', trim($lines[1]));
        $arrival->importer = iconv('iso-8859-1', 'UTF-8//TRANSLIT', substr($lines[2], 0, strpos($lines[2], '"')));
        $arrival->vintage = $line2Data[0];
        $arrival->saqCode = $line2Data[1];
        preg_match('/^(?P<ml>[0-9]+) .*$/', $line2Data[2], $matches);
        $arrival->milliliters = $matches['ml'];
        $arrival->price = substr(trim($line2Data[3]), 0, -1);

        return $arrival;
    }

    /**
     * Returns the image that will appear in lists for type
     */
    public function getVignette()
    {
        $value = $this->getRegion() == 'Mousseux' || $this->getRegion() == 'Champagne' ? 'vin_mousseux_' : 'vin_';
        $value .= strtolower(str_replace(array("é"), array("e"), $this->getColor()));
        $value = in_array($value, array('vin_blanc', 'vin_rouge', 'vin_rose',
            'vin_mousseux_blanc', 'vin_mousseux_rouge', 'vin_mousseux_rose'))
            ? $value : "unknown";

        switch (true) {
            case $value == 'unknown':
                break;
            case $this->getMilliliters() < 750:
                $value = $value . '-smaller';
                break;
            case $this->getMilliliters() > 750:
                $value = $value . '-bigger';
                break;
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getSaqCode()
    {
        return $this->saqCode;
    }

    /**
     * @return string
     */
    public function getArrivalCode()
    {
        return $this->arrivalCode;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * The subregion is only given for France and Italy, and only for regular formats
     * @return string
     */
    public function getSubregion()
    {
        //Special case for champagne, as usual... ^^
        if ($this->region == 'Champagne') {
            return $this->region;
        }

        $matches = array();
        if (!preg_match('/^Vin (bl|rg) (Italie|France) (?P<subregion>.*)$/', $this->region, $matches)) {
            return null;
        }
        return $matches['subregion'];
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getImporter()
    {
        return $this->importer;
    }

    /**
     * @return int
     */
    public function getMilliliters()
    {
        return $this->milliliters;
    }

    /**
     * @return string
     */
    public function getProducer()
    {
        return $this->producer;
    }

    /**
     * @return string
     */
    public function getVintage()
    {
        return $this->vintage;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}