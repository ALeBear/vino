<?php

namespace vino;

use horses\plugin\auth\AbstractUser;

/**
 * A user
 * @Entity @HasLifecycleCallbacks
 */
class User extends AbstractUser
{
    /**
     * @OneToMany(targetEntity="WinesList", mappedBy="user")
     * @var WinesList[]
     */
     protected $lists;
     
    /**
     * @Column(type="string", length=1000, nullable=true)
     * @var string
     */
    protected $settingsRaw;
     
    /**
     * @var Mixed[]
     */
    protected $settings = array();
    
    
    /**
     * Sets the wine Ids array after loading
     * @PostLoad
     */
    public function unpack()
    {
        $this->settings = $this->settingsRaw ? @json_decode($this->settingsRaw, true) : array();
        $this->settings || $this->settings = array();
    }
    
    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getSetting($name, $default = null)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : $default;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return \vino\User
     */
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;
        $this->settingsRaw = json_encode($this->settings);
        
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return (bool) $this->getSetting('isAdmin');
    }
}