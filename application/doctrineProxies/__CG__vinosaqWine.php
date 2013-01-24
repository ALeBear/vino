<?php

namespace Proxies\__CG__\vino\saq;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Wine extends \vino\saq\Wine implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function unpack()
    {
        $this->__load();
        return parent::unpack();
    }

    public function getCode()
    {
        $this->__load();
        return parent::getCode();
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function getAppelation()
    {
        $this->__load();
        return parent::getAppelation();
    }

    public function getCategorie()
    {
        $this->__load();
        return parent::getCategorie();
    }

    public function getPays()
    {
        $this->__load();
        return parent::getPays();
    }

    public function getPourcentage()
    {
        $this->__load();
        return parent::getPourcentage();
    }

    public function getPrix()
    {
        $this->__load();
        return parent::getPrix();
    }

    public function getFournisseur()
    {
        $this->__load();
        return parent::getFournisseur();
    }

    public function getImage()
    {
        $this->__load();
        return parent::getImage();
    }

    public function getThumbnail()
    {
        $this->__load();
        return parent::getThumbnail();
    }

    public function getCepage()
    {
        $this->__load();
        return parent::getCepage();
    }

    public function getCouleur()
    {
        $this->__load();
        return parent::getCouleur();
    }

    public function getFormat()
    {
        $this->__load();
        return parent::getFormat();
    }

    public function getNature()
    {
        $this->__load();
        return parent::getNature();
    }

    public function getRegion()
    {
        $this->__load();
        return parent::getRegion();
    }

    public function __toString()
    {
        $this->__load();
        return parent::__toString();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'data', 'lang', 'code');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}