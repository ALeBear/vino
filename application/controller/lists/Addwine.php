<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use InvalidArgumentException;

/**
 * Adds a wine to a list
 */
class Addwine extends VinoAbstractController
{
    /**
     * Used to filter query (and path) parameters before passing them to magic
     * methods
     * @param string $name
     * @param mixed $value
     * @return mixed the filtered out value
     */
    public function filterMagicParam($name, $value)
    {
        switch ($name) {
            case 'l':
                return preg_replace('/[^\d]/', '', $value);
            default:
                return parent::filterMagicParam($name, $value);
        }
    }
    
    public function execute($l, $c, $f)
    {
        $list = $this->getEntityManager()
            ->getRepository('vino\\WinesList')
            ->findOneBy(array('user' => $this->getUser(), 'id' => $l));
        if (!$list) {
            throw new InvalidArgumentException(sprintf('Invalid list id: %s', $l));
        }
        
        $wine = $this->getWine($c);
        if (!$wine) {
            throw new InvalidArgumentException(sprintf('Invalid wine code: %s', $c));
        }
        
        $list->addWine($wine);
        $this->getEntityManager()->persist($list);
        $this->getEntityManager()->flush();
        
        $this->router->redirectExternal($this->getBackUrl($f));
    }
    
    /**
     * Does this controller have a view?
     * @return boolean
     */
    public function hasView()
    {
        return false;
    }
}