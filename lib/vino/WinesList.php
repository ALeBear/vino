<?php

namespace vino;

/**
 * A list of wines
 * @Entity
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
     * @ManyToMany(targetEntity="UserWine", inversedBy="lists")
     * @JoinTable(name="wine_listings",
     *   joinColumns={@JoinColumn(name="list_id", referencedColumnName="id")},
     *   inverseJoinColumns={@JoinColumn(name="wine_id", referencedColumnName="id")}
     * )
     * @param Doctrine\ORM\PersistentCollection
     */
    protected $wines;
    
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
     * @return string
     */
    public function count()
    {
        return count($this->getWines());
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
        return $this->wines ? $this->wines->contains($wine) : false;
    }
    
    /**
     * @param \vino\UserWine $wine
     * @return vino\WinesList $this
     */
    public function addWine(UserWine $wine)
    {
        if (!$this->contains($wine)) {
            $this->wines[] = $wine;
            $wine->addToList($this);
        }
        
        return $this;
    }
    
    public function removeWine(UserWine $wine)
    {
        $this->wines && $this->contains($wine) && $this->wines->removeElement($wine);
        $wine->removeFromList($this);
        return $this;
    }
    
    /**
     * @return type
     */
    public function getWines()
    {
        return $this->wines;
    }
}