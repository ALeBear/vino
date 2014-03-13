<?php

namespace vino\saq;

use vino\User;

/**
 * A watchlist of arrivals
 * @Entity @HasLifecycleCallbacks
 */
class ArrivalWatchlist
{
    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     * @param integer
     */
    protected $id;

    /**
     * @OneToOne(targetEntity="vino\User")
     * @var User
     */
     protected $user;
    
    /**
     * @Column(type="string", length=1000, nullable=true)
     * @var string
     */
    protected $arrivals;
    
    /**
     * The arrival Ids, exploded
     * @var array
     */
    protected $arrivalIds = array();

    
    /**
     * Create a brand new watchlist ready to be persisted
     * @param User $user
     * @return ArrivalWatchlist
     */
    public static function create(User $user)
    {
        $list = new self();
        $list->user = $user;
        
        return $list;
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the wine Ids array after loading
     * @PostLoad
     */
    public function unpack()
    {
        $this->arrivalIds = $this->arrivals ? explode(',', $this->arrivals) : array();
    }
    
    /**
     * @return string
     */
    public function count()
    {
        return count($this->arrivalIds);
    }
    
    /**
     * @return \vino\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add or remove an arrival
     * @param Arrival $arrival
     * @return $this
     */
    public function addRemoveArrival(Arrival $arrival)
    {
        if ($this->contains($arrival->getId())) {
            unset($this->arrivalIds[array_search($arrival->getId(), $this->arrivalIds)]);
        } else {
            $this->arrivalIds[] = $arrival->getId();
        }
        $this->arrivals = implode(',', $this->arrivalIds);

        return $this;
    }

    /**
     * @return integer[]
     */
    public function getArrivalIds()
    {
        return $this->arrivalIds;
    }
    
    /**
     * @param integer $id
     * @return boolean
     */
    public function contains($id)
    {
        return in_array($id, $this->arrivalIds);
    }
}