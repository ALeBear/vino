<?php

namespace vino\saq;

use stdClass;

/**
 * The details of a wine from SAQ
 * @Entity @HasLifecycleCallbacks
 */
class Wine
{
    /**
     * Maximum lifetime in days
     */
    const MAX_LIFETIME = 10;

    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     * @param integer
     */
    protected $id;
    
    /**
     * @Column(type="string", length=4000)
     * @var string
     */
    protected $data;
    
    /**
     * @Column(type="string", length=2)
     * @var string
     */
    protected $lang;

    /**
     * @Column(type="string", length=15)
     * @var string
     */
    protected $code;

    /**
     * @Column(type="datetime")
     * @var string
     */
    protected $lastUpdate;

    /**
     * @var string
     */
    protected $description;
    
    /**
     * @var string
     */
    protected $identiteProduit;
    
    /**
     * @var string
     */
    protected $format;
    
    /**
     * @var string
     */
    protected $prix;
    
    /**
     * @var string
     */
    protected $prixReduit;

    /**
     * @var string
     */
    protected $millesime;

    /**
     * @var boolean Null is unknown
     */
    protected $indDispoEnLigne = null;

    /**
     * @var integer
     */
    protected $qteDispoEnLigne;

    /**
     * @var boolean Null is unknown
     */
    protected $indDispoEnSuccursale = null;

    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * @param \vino\saq\Wine $wine
     * @param string $lang
     * @param stdClass $parameters
     * @return \vino\saq\Wine
     */
    public static function updateFromSaq(Wine $wine, $lang, stdClass $parameters)
    {
        $wine->lang = $lang;
        $wine->lastUpdate = new \DateTime('now');
        self::updateWineObject($wine, $parameters);

        return $wine;
    }

    /**
     * @param string $lang
     * @param stdClass $parameters
     * @return \vino\saq\Wine
     */
    public static function fromSaq($lang, stdClass $parameters)
    {
        $wine = new static();
        $wine->lang = $lang;
        $wine->lastUpdate = new \DateTime('now');
        self::updateWineObject($wine, $parameters);

        return $wine;
    }

    /**
     * @param \vino\saq\Wine $wine
     * @param stdClass $saqData
     */
    protected static function updateWineObject(Wine $wine, stdClass $saqData)
    {
        $data = array();

        //Basic params
        isset($saqData->partNumber) && $wine->code = $saqData->partNumber;
        foreach (array_keys(get_object_vars($wine)) as $property) {
            if ($property == 'id') {
                continue;
            }

            if (isset($saqData->$property)) {
                $wine->$property = $data[$property] = $saqData->$property;
            }
        }
        //Attributes
        $data['attributes'] = array();
        if (isset($saqData->listeAttributs) && is_array($saqData->listeAttributs)) {
            foreach ($saqData->listeAttributs as $attribute) {
                if (isset($data['attributes'][$attribute->typeAttribut])) {
                    is_array($data['attributes'][$attribute->typeAttribut]) || $data['attributes'][$attribute->typeAttribut] = array($data['attributes'][$attribute->typeAttribut]);
                    $data['attributes'][$attribute->typeAttribut][] = $attribute->value;
                } else {
                    $data['attributes'][$attribute->typeAttribut] = $attribute->value;
                }
            }
        }
        $wine->attributes = $data['attributes'];

        $wine->data = json_encode($data);
    }
    
    /**
     * Sets the properties after loading from DB from the data json array
     * @PostLoad
     */
    public function unpack()
    {
        $data = $this->data ? json_decode($this->data, true) : array();
        foreach ($data as $property => $value) {
            $this->$property = $data[$property];
        }
    }
    
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @return string
     */
    public function getAppelation()
    {
        return $this->getAttribute('APPELLATION');
    }
    
    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->identiteProduit;
    }

    /**
     * @return string
     */
    public function getMillesime()
    {
        return $this->millesime;
    }

    /**
     * @return string
     */
    public function getPays()
    {
        return $this->getAttribute('PAYS_ORIGINE');
    }
    
    /**
     * @return string
     */
    public function getPourcentage()
    {
        return $this->getAttribute('POURCENTAGE_ALCOOL_PAR_VOLUME');
    }
    
    /**
     * @param boolean $original
     * @return float
     */
    public function getPrix($original = false)
    {
        return number_format($original || !$this->hasPrixReduit() ? $this->prix : $this->prixReduit, 2);
    }
    
    /**
     * @return boolean
     */
    public function hasPrixReduit()
    {
        return (bool) $this->prixReduit;
    }
    
    /**
     * @return string
     */
    public function getPrixReduit()
    {
        return number_format($this->prixReduit, 2);
    }
    
    /**
     * @return string
     */
    public function getFournisseur()
    {
        return $this->getAttribute('NOM_PRODUCTEUR');
    }
    
    /**
     * @return string
     */
    public function getImage()
    {
        return sprintf('http://s7d9.scene7.com/is/image/SAQ/%s_is?$saq-rech-prod-list$', $this->getCode());
    }
    
    /**
     * @return string
     */
    public function getThumbnail()
    {
        return sprintf('http://s7d9.scene7.com/is/image/SAQ/%s_is?$saq-prod-sugg$', $this->getCode());
    }
    
    /**
     * @return string
     */
    public function getCepage()
    {
        $attr = $this->getAttribute('CEPAGE');
        return is_array($attr) ? implode(', ', $attr) : $attr;
    }
    
    /**
     * @return string
     */
    public function getCouleur()
    {
        return $this->getAttribute('COULEUR');
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return boolean Null for unknown
     */
    public function isDispoEnLigne()
    {
        return $this->indDispoEnLigne;
    }

    /**
     * @return integer
     */
    public function qteDispoEnLigne()
    {
        return (int) $this->qteDispoEnLigne;
    }

    /**
     * @return boolean Null for unknown
     */
    public function isDispoEnSuccursale()
    {
        return $this->indDispoEnSuccursale;
    }

    /**
     * @return string
     */
    public function getFormatInMl()
    {
        list($value, $unit) = explode(' ', $this->format);
        return $unit == 'ml' ? $value : $value * 1000;
    }
    
    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->getPays() . ($this->getAttribute('REGION_ORIGINE') ? '/' . $this->getAttribute('REGION_ORIGINE') : '');
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->description . ($this->millesime ? ' ' . $this->millesime : '');
    }
    
    /**
     * Returns the image that will appear in lists for type
     */
    public function getVignette()
    {
        $value = strtolower(str_replace(array(" ", "é", "tranquille"), array("_", "e", ""), trim(sprintf("%s %s", $this->getCategorie(), (string) $this->getCouleur()))));
        $value = in_array($value, array('vin_de_dessert', 'sauternes', 'vin_blanc',
            'vin_blanc_blanc', 'vin_rouge', 'vin_rouge_rouge', 'vin_rose', 'vin_rose_rose',
            'vin_mousseux_blanc', 'vin_mousseux_rouge', 'vin_mousseux_rose', 'champagne',
            'champagne_blanc', 'champagne_rose', 'champagne_rose_rose', 'vin_mousseux'))
            ? $value : "unknown";
        
        switch (true) {
            case $value == 'unknown':
                break;
            case $this->getFormatInMl() < 750:
                $value = $value . '-smaller';
                break;
            case $this->getFormatInMl() > 750:
                $value = $value . '-bigger';
                break;
        }
        
        return $value;
    }
    
    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    protected function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }
}
