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
     * @Id @Column(type="integer") @GeneratedValue 
     * @param integer
     */
    protected $id;
    
    /**
     * @Column(type="string", length=1000)
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
    protected $partNumber;
    
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
     * @var array
     */
    protected $attributes = array();
    
    /**
     * @param stdClass $parameters
     * @return vino\saq\Wine
     */
    public static function fromSaq($lang, stdClass $parameters)
    {
        $wine = new static();
        $wine->lang = $lang;
        $data = array();
        
        //Basic params
        foreach (array_keys(get_object_vars($wine)) as $property) {
            if (isset($parameters->$property)) {
                $wine->$property = $data[$property] = $parameters->$property;
            }
        }
        //Attributes
        $data['attributes'] = array();
        if (isset($parameters->listeAttributes) && is_array($parameters->listeAttributes)) {
            foreach ($parameters->listeAttributes as $attribute) {
                if (isset($data['attributes'][$attribute->typeAttribut])) {
                    is_array($data['attributes'][$attribute->typeAttribut]) || $data['attributes'][$attribute->typeAttribut] = array($data['attributes'][$attribute->typeAttribut]);
                    $data['attributes'][$attribute->typeAttribut][] = $attribute->value;
                } else {
                    $data['attributes'][$attribute->typeAttribut] = $attribute->value;
                }
            }
        }
        
        $wine->data = json_encode($data);
        
        return $wine;
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
        return $this->partNumber;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->description;
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
     * @return string
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
        return sprintf('http://s7d9.scene7.com/is/image/SAQ/%s_is?$saq/prod$', $this->getCode());
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
        return $this->getAttribute('CEPAGE');
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
     * @return string
     */
    public function getRegion()
    {
        return $this->pays . '/' . $this->getAttribute('REGION_ORIGINE');
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->description;
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