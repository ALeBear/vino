<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use InvalidArgumentException;

/**
 * Watch a wine
 */
class Watchwine extends VinoAbstractController
{
    /**
     * Used to filter query (and path) parameters before passing them to magic
     * methods
     * @param string $name
     * @param mixed $value
     * @return mixed the filtered out value
     */
    protected function filterMagicParam($name, $value)
    {
        switch ($name) {
            case 'l':
                return preg_replace('/[^\d]/', '', $value);
            default:
                return parent::filterMagicParam($name, $value);
        }
    }
    
    protected function execute($l, $c, $f)
    {
        $wine = $this->getWine($c);
        if (!$wine) {
            throw new InvalidArgumentException(sprintf('Invalid wine code: %s', $c));
        }

        if (in_array($c, $this->getUser()->getWatchingWineIds())) {
            $this->getUser()->removeFromWatchingWines($c);
        } else {
            $this->getUser()->addToWatchingWines($c);
        }
        $this->getEntityManager()->persist($this->getUser());
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
