<?php

namespace vino\saq;

use stdClass;

/**
 * A Point Of Sale (A SAQ)
 * @Entity
 */
class Pos
{
    const EARTH_RADIUS = 6371;
    
    
    /**
     * @Id @Column(type="integer") 
     * @var integer
     */
    protected $id;
    
    /**
     * @Column(type="string", length=500)
     * @var string
     */
    protected $address;

    /**
     * @Column(type="string", length=30)
     * @var string
     */
    protected $classification;
    
    /**
     * @Column(type="decimal", precision=10, scale=6)
     * @var float
     */
    protected $latitude;
    
    /**
     * @Column(type="decimal", precision=10, scale=6)
     * @var float
     */
    protected $longitude;
    
    /**
     * @Column(type="string", length=100)
     * @var string
     */
    protected $city;
    
    
    /**
     * @param stdClass $parameters
     * @return \vino\saq\Pos
     */
    public static function fromSaq(stdClass $parameters)
    {
        $pos = new static();
        $pos->address = $parameters->adresse;
        $pos->classification = $parameters->banniere;
        $pos->latitude = $parameters->latitude;
        $pos->longitude = $parameters->longitude;
        $pos->id = $parameters->succursaleId;
        $pos->city = $parameters->ville;
        
        return $pos;
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->classification;
    }
    
    /**
     * @return string
     */
    public function getLat()
    {
        return $this->latitude;
    }
    
    /**
     * @return string
     */
    public function getLong()
    {
        return $this->longitude;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s, %s (%s)', $this->address, $this->city, $this->classification);
    }
    
    /**
     * @param float $lat
     * @param float $long
     * @return float In KM
     */
    public function getDistanceTo($lat, $long)
    {
        return self::calculateDistance($this->latitude, $this->longitude, $lat, $long);
    }
    
    /**
     * @param float $lat1
     * @param float $long1
     * @param float $lat2
     * @param float $long2
     * @return int In KM
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $dLat = self::toRad($lat2 - $lat1);
        $dLon = self::toRad($lon2 - $lon1); 
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(self::toRad($lat1)) * cos(self::toRad($lat2)) * 
            sin($dLon / 2) * sin($dLon / 2); 
        
        return self::EARTH_RADIUS * 2 * atan2(sqrt($a), sqrt(1 - $a)); 
    }
    
    /**
     * @param float $value
     * @return float
     */
    protected static function toRad($value)
    {
        return $value * pi() / 180;
    }
}
