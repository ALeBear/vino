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
    protected $code;
    
    protected $name;
    protected $appelation;
    protected $categorie;
    protected $cepage;
    protected $couleur;
    protected $format;
    protected $fournisseur;
    protected $image;
    protected $thumbnail;
    protected $pays;
    protected $pourcentage;
    protected $prix;
    protected $region;
    protected $sousRegion;
    protected $nature;
    
    /**
     * @param stdClass $parameters
     * @return vino\saq\Wine
     */
    public static function fromSaq($lang, stdClass $parameters)
    {
        $wine = new static();
        $wine->lang = $lang;
        $data = array();
        foreach (array_keys(get_object_vars($wine)) as $property) {
            isset($parameters->$property) && $wine->$property = Webservice::decodeCdata($parameters->$property);
            if (!in_array($property, array('code', 'id', 'data'))) {
                $data[$property] = $wine->$property;
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
        return $this->code;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getAppelation()
    {
        return $this->appelation;
    }
    
    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    /**
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }
    
    /**
     * @return string
     */
    public function getPourcentage()
    {
        return $this->pourcentage;
    }
    
    /**
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }
    
    /**
     * @return string
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }
    
    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }
    
    /**
     * @return string
     */
    public function getCepage()
    {
        return $this->cepage;
    }
    
    /**
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
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
    public function getNature()
    {
        return $this->nature;
    }
    
    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->pays . '/' . $this->region;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}