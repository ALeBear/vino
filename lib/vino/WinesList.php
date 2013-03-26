<?php

namespace vino;

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
     * @Column(type="string", length=1000)
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
     * Sets the wine Ids array after loading
     * @PostLoad
     */
    public function unpack()
    {
        $this->wineIds = explode(',', $this->wines);
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
     * @param \vino\UserWine $wine
     * @return boolean
     */
    public function contains(UserWine $wine)
    {
        return in_array($wine->getCode(), $this->wineIds);
    }
    
    /**
     * @param \vino\UserWine $wine
     * @return vino\WinesList $this
     */
    public function addWine(UserWine $wine)
    {
        if (!$this->contains($wine)) {
            $this->wineIds[] = $wine->getCode();
            $this->wines = implode(',', $this->wineIds);
        }
        
        return $this;
    }
    
    public function removeWine(UserWine $wine)
    {
        if ($this->contains($wine)) {
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
}