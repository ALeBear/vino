<?php

namespace vino;

use Doctrine\ORM\EntityManager;
use horses\plugin\auth\AbstractUser;
use vino\saq\Webservice;

/**
 * A user
 * @Entity @HasLifecycleCallbacks
 */
class User extends AbstractUser
{
    const DEFAULT_CLOSE_POS_COUNT = 3;
    const DEFAULT_WATCHED_WINE_AVAILABILITY_THRESHOLD = 6;


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
     * @Column(type="string", length=1000, nullable=true)
     * @var string
     */
    protected $watchingPosRaw;

    /**
     * @Column(type="string", length=1000, nullable=true)
     * @var string
     */
    protected $watchingWinesRaw;

    /**
     * @var integer[]
     */
    protected $favoritePos = array();

    /**
     * @var integer[]
     */
    protected $watchingPos = array();

    /**
     * @var integer[]
     */
    protected $watchingWines = array();

    
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
        $this->watchingPos = $this->watchingPosRaw ? @json_decode($this->watchingPosRaw, true) : array();
        is_array($this->watchingPos) || $this->watchingPos = array();
        $this->watchingWines = $this->watchingWinesRaw ? @json_decode($this->watchingWinesRaw, true) : array();
        is_array($this->watchingWines) || $this->watchingWines = array();
    }
    
    /**
     * @param string $name
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
     * @return mixed
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
            case 'watchingWineAlertThreshold':
                //Minimum availability under which (so not equal) the user gets an alert for watched wines
                return self::DEFAULT_WATCHED_WINE_AVAILABILITY_THRESHOLD;
            case 'lang':
                return 'fr_CA';
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

    /**
     * @return integer[]
     */
    public function getWatchingPosIds()
    {
        return $this->watchingPos;
    }

    /**
     * @param integer $id
     * @return \vino\User
     */
    public function addToWatchingPos($id)
    {
        if (array_search($id, $this->watchingPos) === false) {
            $this->watchingPos[] = $id;
            $this->watchingPosRaw = json_encode($this->watchingPos);
        }

        return $this;
    }

    /**
     * @param integer $id
     * @return \vino\User
     */
    public function removeFromWatchingPos($id)
    {
        if (array_search($id, $this->watchingPos) !== false) {
            unset($this->watchingPos[array_search($id, $this->watchingPos)]);
            $this->watchingPosRaw = json_encode($this->watchingPos);
        }

        return $this;
    }

    /**
     * @return integer[]
     */
    public function getWatchingWineIds()
    {
        return array_keys($this->watchingWines);
    }

    /**
     * @return integer[]
     */
    public function getWatchingWinesWithQuantities()
    {
        return $this->watchingWines;
    }

    /**
     * @param integer $id
     * @return \vino\User
     */
    public function addToWatchingWines($id)
    {
        if (array_search($id, $this->watchingWines) === false) {
            $this->watchingWines[$id] = 9999;
            $this->watchingWinesRaw = json_encode($this->watchingWines);
        }

        return $this;
    }

    /**
     * @param integer $id
     * @return \vino\User
     */
    public function removeFromWatchingWines($id)
    {
        if (array_key_exists($id, $this->watchingWines) !== false) {
            unset($this->watchingWines[$id]);
            $this->watchingWinesRaw = json_encode($this->watchingWines);
        }

        return $this;
    }

    /**
     * @param integer $wineId
     * @param integer $quantity
     * @return \vino\User
     */
    public function setWatchingWineQuantity($wineId, $quantity)
    {
        if (array_key_exists($wineId, $this->watchingWines) !== false) {
            $this->watchingWines[$wineId] = $quantity;
            $this->watchingWinesRaw = json_encode($this->watchingWines);
        }

        return $this;
    }

    /**
     * @param EntityManager $em
     * @param Webservice $saqWebservice
     * @return array|void
     */
    public function getWatchingWinesQuantities(EntityManager $em, Webservice $saqWebservice)
    {
        $watchedWineIds = $this->getWatchingWineIds();
        if (!$watchedWineIds || !is_array($watchedWineIds) || !count($watchedWineIds)) {
            return array();
        }

        $watchedPosIds = $this->getWatchingPosIds();
        if (!$watchedPosIds || !is_array($watchedPosIds) || !count($watchedPosIds)) {
            return array();
        }

        $poses = array();
        foreach ($watchedPosIds as $posId) {
            $poses[$posId] = $em
                ->getRepository('vino\\saq\\Pos')
                ->find($posId);
        }

        $quantities = array();
        foreach ($watchedWineIds as $wineId) {
            $availabilities = $saqWebservice->getAvailabilityByWineCode($wineId);
            $maximumQuantity = 0;
            $maximumQuantityPos = null;
            foreach ($availabilities as $availability) {
                if (!in_array($availability->getPos()->getId(), $watchedPosIds)) {
                    continue;
                }

                if ($availability->getQuantity() > $maximumQuantity) {
                    $maximumQuantity = $availability->getQuantity();
                    $maximumQuantityPos = $availability->getPos();
                }
            }

            $quantities[$wineId] = array(
                'maximumQuantity' => $maximumQuantity,
                'maximumQuantityPos' => $maximumQuantityPos,
            );
        }

        return $quantities;
    }
}
