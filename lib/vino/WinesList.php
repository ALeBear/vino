<?php

namespace vino;

use vino\saq\Wine;

/**
 * A list of wines
 * @Entity @HasLifecycleCallbacks
 */
class WinesList
{
    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     * @param integer
     */
    protected $id;

    /**
     * @Column(type="string", length=100)
     * @var string
     */
    protected $name;
    
    /**
     * @ManyToOne(targetEntity="User", inversedBy="lists")
     * @var User
     */
     protected $user;
    
    /**
     * @Column(type="string", length=1000, nullable=true)
     * @var string
     */
    protected $wines;
    
    /**
     * The wine Ids, exploded
     * @var array
     */
    protected $wineIds = array();

    
    /**
     * Create a brand new list ready to be persisted
     * @param string $name
     * @param \vino\User $user
     * @return \vino\WinesList
     */
    public static function create($name, User $user)
    {
        $list = new static();
        $list->name = $name;
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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
    
    /**
     * @param string $value
     * @return \vino\WinesList $this
     */
    public function setName($value)
    {
        $this->name = $value;
        
        return $this;
    }
    
    /**
     * Sets the wine Ids array after loading
     * @PostLoad
     */
    public function unpack()
    {
        $this->wineIds = $this->wines ? explode(',', $this->wines) : array();
    }
    
    /**
     * @return string
     */
    public function count()
    {
        return count($this->wineIds);
    }
    
    /**
     * @return vino\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @param \vino\saq\Wine $wine
     * @return vino\WinesList $this
     */
    public function addWine(Wine $wine)
    {
        if (!$this->contains($wine->getCode())) {
            $this->wineIds[] = $wine->getCode();
            $this->wines = implode(',', $this->wineIds);
        }
        
        return $this;
    }
    
    /**
     * 
     * @param \vino\saq\Wine $wine
     * @return \vino\WinesList $this
     */
    public function removeWine(Wine $wine)
    {
        if ($this->contains($wine->getCode())) {
            unset($this->wineIds[array_search($wine->getCode(), $this->wineIds)]);
            $this->wines = implode(',', $this->wineIds);
        }
        return $this;
    }
    
    /**
     * @return type
     */
    public function getWineIds()
    {
        return $this->wineIds;
    }
    
    /**
     * @param string $code
     * @return boolean
     */
    public function contains($code)
    {
        return in_array($code, $this->wineIds);
    }
}