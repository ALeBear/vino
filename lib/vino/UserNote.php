<?php

namespace vino;

use InvalidArgumentException;

/**
 * A note about a wine
 * @Entity
 */
class UserNote
{
    const NO_VINTAGE = 'NV';
    
    
    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     * @param integer
     */
    protected $id;
    
    /**
     * @Column(type="string", length=15)
     * @var string
     */
    protected $wineCode;
    
    /**
     * @ManyToOne(targetEntity="User", inversedBy="lists")
     * @var User
     */
     protected $user;
    
    /**
     * @Column(type="string", length=4)
     * @var string
     */
     protected $vintage = 'NV';
    
    /**
     * Appreciation value 0-100
     * @Column(type="integer")
     * @var integer
     */
    protected $appreciation;
    
    /**
     * @Column(type="string", length=500)
     * @var string
     */
    protected $text;
    
     
    /**
     * Create a brand new Wine ready to be persisted
     * @param string $wineCode
     * @param WinesList $user
     * @param integer $vintage Not given = No Vintage (NV)
     * @return \vino\UserNote
     */
    public static function create($wineCode, User $user, $vintage = self::NO_VINTAGE)
    {
        $note = new static();
        $note->wineCode = $wineCode;
        $note->user = $user;
        $note->vintage = $vintage;
        
        return $note;
    }
    
    /**
     * @param type $text
     * @return \vino\UserNote
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
    
    /**
     * @param integer $value
     * @return \vino\UserNote
     */
    public function setAppreciation($value)
    {
        $this->appreciation = $value;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getWineCode()
    {
        return $this->wineCode;
    }
    
    /**
     * @return string
     */
    public function getVintage()
    {
        return $this->vintage;
    }
    
    /**
     * @return integer
     */
    public function getAppreciation()
    {
        return $this->appreciation;
    }
    
    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * @return vino\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
