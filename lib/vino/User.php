<?php

namespace vino;

use horses\plugin\auth\AbstractUser;

/**
 * A user
 * @Entity @HasLifecycleCallbacks
 */
class User extends AbstractUser
{
    const DEFAULT_CLOSE_POS_COUNT = 3;
    
    
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
     * @Column(type="string", length=1000, nullable=true)
     * @var string
     */
    protected $favoritePosRaw;
     
    /**
     * @var integer[]
     */
    protected $favoritePos = array();
    
    
    /**
     * Sets the wine Ids array after loading
     * @PostLoad
     */
    public function unpack()
    {
        $this->settings = $this->settingsRaw ? @json_decode($this->settingsRaw, true) : array();
        is_array($this->settings) || $this->settings = array();
        $this->favoritePos = $this->favoritePosRaw ? @json_decode($this->favoritePosRaw, true) : array();
        is_array($this->favoritePos) || $this->favoritePos = array();
    }
    
    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getSetting($name)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : self::getDefaultSetting($name);
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
     * @param string $name
     */
    public static function getDefaultSetting($name)
    {
        switch ($name) {
            case 'closePosCount':
                //Number of closest POS to return for whole listing availability
                return self::DEFAULT_CLOSE_POS_COUNT;
            case 'availabilityDisplayLowerLimit':
                //At or under this number, item is considered not available
                return 0;
            case 'availabilityHideOnline':
                //Calculate SAQ.com availability?
                return false;
            default:
                return null;
        }
    }
    
    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return (bool) $this->getSetting('isAdmin');
    }
    
    /**
     * @param string $name
     * @return integer[]
     */
    public function getFavoritePosIds()
    {
        return $this->favoritePos;
    }
    
    /**
     * @param integer $id
     * @return \vino\User
     */
    public function addToFavoritePos($id)
    {
        if (array_search($id, $this->favoritePos) === false) {
            $this->favoritePos[] = $id;
            $this->favoritePosRaw = json_encode($this->favoritePos);
        }
        
        return $this;
    }
    
    /**
     * @param integer $id
     * @return \vino\User
     */
    public function removeFromFavoritePos($id)
    {
        if (array_search($id, $this->favoritePos) !== false) {
            unset($this->favoritePos[array_search($id, $this->favoritePos)]);
            $this->favoritePosRaw = json_encode($this->favoritePos);
        }
        
        return $this;
    }
}