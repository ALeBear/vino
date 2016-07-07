<?php

namespace horses\controller\search;

use vino\VinoAbstractController;

/**
 * Search homepage
 */
class Index extends VinoAbstractController
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
            case 'p':
                return (int) preg_replace('/[^\d]/', '', $value);
            case 'q':
                return preg_replace('/[^\d\w -\.]/u', '', urldecode($value));
            default:
                return parent::filterMagicParam($name, $value);
        }
    }
    
    protected function prepareView($q = '', $p = 0)
    {
        $this->view->products = array();
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        $this->metas['title'] = $this->_('title');
        $this->view->currentPage = $p;
        
        if ($this->view->query = $q) {
            $this->metas['title'] = $this->_('title_query');
            $searchResults = $this->getSaqWebservice()->searchWinesByKeyword($q, $p);
            $this->view->pages = $searchResults['pages'];
            $this->view->wines = $searchResults['wines'];
            $this->view->noResults = !count($this->view->wines);
        } else {
            $this->view->wines = array();
            $this->view->pages = 0;
            $this->view->noResults = false;
        }
        
        $this->view->pagingUrlTemplate = $this->router->buildRoute('search/', array('q' => $q, 'p' => 'xxXXxx'))->getUrl();;
        $this->view->searchUrl = $this->router->buildRoute('search/')->getUrl();
        $this->view->from = 's-' . $q;
        $this->view->isWatchedList = false;
    }
}
