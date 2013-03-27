<?php

namespace vino\saq;

use stdClass;

class Pos
{
    protected $address;
    protected $type;
    protected $lat;
    protected $long;
    
    /**
     * @param stdClass $parameters
     */
    public function __construct(stdClass $parameters)
    {
        $this->address = $parameters->adresse;
        $this->type = $parameters->banniere;
        $this->lat = $parameters->latitude;
        $this->long = $parameters->longitude;
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
        return $this->type;
    }
    
    /**
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }
    
    /**
     * @return string
     */
    public function getLong()
    {
        return $this->long;
    }
}
