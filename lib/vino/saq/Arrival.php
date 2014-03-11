<?php

namespace vino\saq;

use \InvalidArgumentException;

/**
 * The details of a wine from SAQ
 * @Entity @HasLifecycleCallbacks
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
     * @Column(type="datetime")
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
     * @var integer
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
        $arrival->country = $line0Data[1];
        $arrival->region = $line0Data[2];
        $arrival->color = $line0Data[3];
        $arrival->name = trim(substr($lines[0], strpos($lines[0], '"') + 1));
        $arrival->producer = trim($lines[1]);
        $arrival->importer = substr($lines[2], 0, strpos($lines[2], '"'));
        $arrival->vintage = $line2Data[0];
        $arrival->saqCode = $line2Data[1];
        $arrival->milliliters = substr($line2Data[2], 0, strpos($line2Data[2], 'ml'));
        $arrival->price = substr(trim($line2Data[3]), 0, -1);

        return $arrival;
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

}