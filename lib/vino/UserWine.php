<?php

namespace vino;

use InvalidArgumentException;

/**
 * An entry in one or more lists of wines
 * @Entity
 */
class UserWine
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
    protected $code;
    
    /**
     * @ManyToMany(targetEntity="WinesList", mappedBy="wines")
     * @param Doctrine\ORM\PersistentCollection
     */
    protected $lists;
    
    /**
     * @ManyToOne(targetEntity="User", inversedBy="lists")
     * @var User
     */
     protected $user;
    
    /**
     * @ManyToOne(targetEntity="vino\saq\Wine")
     * @var User
     */
     protected $saqWine;
    
     
    /**
     * Create a brand new Wine ready to be persisted
     * @param vino\saq\Wine $saqWine
     * @param WinesList $user
     * @return \vino\UserWine
     */
    public static function create($saqWine, User $user)
    {
        $wine = new static();
        $wine->saqWine = $saqWine;
        $wine->code = $saqWine->getCode();
        $wine->user = $user;
        
        return $wine;
    }
    
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * @return vino\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @return vino\saq\Wine
     */
    public function getSaqWine()
    {
        return $this->saqWine;
    }
    
    /**
     * @param \vino\WinesList $list
     * @return boolean
     */
    public function belongsToList(WinesList $list)
    {
        return $this->lists ? $this->lists->contains($list) : false;
    }
    
    /**
     * @param \vino\WinesList $list
     * @return \vino\UserWine
     * @throws InvalidArgumentException When trying to add to another user's list
     */
    public function addToList(WinesList $list)
    {
        if ($list->getUser()->getId() != $this->getUser()->getId()) {
            throw new InvalidArgumentException(sprintf("Cannot add wine to another user's list (wine id: %s, list id: %s)", $this->id, $list->getId()));
        }
        if (!$this->belongsToList($list)) {
            $this->lists[] = $list;
        }
        
        return $this;
    }
    
    /**
     * @param \vino\WinesList $list
     * @return \vino\UserWine
     */
    public function removeFromList(WinesList $list)
    {
        if ($this->belongsToList($list)) {
            $this->lists->removeElement($list);
        }
        return $this;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->saqWine->getName();
    }
}
