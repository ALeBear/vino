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
        $this->address = Webservice::decodeCdata($parameters->address);
        $this->type = Webservice::decodeCdata($parameters->type);
        $this->lat = Webservice::decodeCdata($parameters->latitude);
        $this->long = Webservice::decodeCdata($parameters->longitude);
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
