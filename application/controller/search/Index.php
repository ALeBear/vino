<?php

namespace horses\controller\search;

use vino\VinoAbstractController;

/**
 * Search homepage
 */
class Index extends VinoAbstractController
{
    public function execute($q = '', $p = 0)
    {
        $this->view->products = array();
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        $this->view->formUrl = $this->router->buildRoute('search/')->getUrl();
        $this->metas['title'] = $this->_('title');
        $this->view->currentPage = (int) preg_replace('/[^\d]/', '', $p);
        $this->view->pages = 0;
        
        if ($this->view->query = preg_replace('/[^\d\w -\.]/u', '', urldecode($q))) {
            $this->metas['title'] = $this->_('title_query');
            $searchResults = $this->dependencyInjectionContainer->get('saq_webservice')->searchWinesByKeyword($this->view->query, $this->view->currentPage);
            $this->view->pages = $searchResults['pages'];
            $this->view->wines = $searchResults['wines'];
        }
        
        $this->view->pagingUrlTemplate = $this->router->buildRoute('search/', array('q' => $this->view->query, 'p' => 'xxXXxx'))->getUrl();;
        
        $this->view->from = 's-' . $this->view->query;
    }
}