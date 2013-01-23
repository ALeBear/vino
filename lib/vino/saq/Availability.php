<?php

namespace vino\saq;

use stdClass;

class Availability
{
    protected $quantity;
    protected $pos;
    
    /**
     * @param stdClass $parameters
     */
    public function __construct(stdClass $parameters)
    {
        $this->quantity = Webservice::decodeCdata($parameters->quantiteProduit);
        $this->pos = new Pos($parameters);
    }
    
    /**
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    /**
     * @return Pos
     */
    public function getPos()
    {
        return $this->pos;
    }
}
