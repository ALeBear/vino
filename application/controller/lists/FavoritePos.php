<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;

/**
 * Manage favorite POS for the current user
 */
class FavoritePos extends VinoAbstractController
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
            case 'action':
                return $value == 'r' ? $value : 'a';
            default:
                return parent::filterMagicParam($name, $value);
        }
    }
    
    protected function execute($id, $action, $f)
    {
        switch ($action) {
            case 'a':
                $this->getUser()->addToFavoritePos($id);
                break;
            case 'r':
                $this->getUser()->removeFromFavoritePos($id);
                break;
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