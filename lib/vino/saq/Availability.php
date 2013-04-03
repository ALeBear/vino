<?php

namespace vino\saq;

use stdClass;

class Availability
{
    protected $quantity;
    protected $pos;
    
    /**
     * @param stdClass $parameters
     * @return \vino\saq\Availability
     */
    public static function fromSaq(stdClass $parameters)
    {
        $avail = new static();
        $avail->quantity = $parameters->nbProduit;
        $avail->pos = Pos::fromSaq($parameters);
        
        return $avail;
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
