<?php

namespace horses\controller\search;

use vino\VinoAbstractController;

/**
 * Search homepage
 */
class Index extends VinoAbstractController
{
    public function execute($q = '')
    {
        $this->view->products = array();
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        $this->view->formUrl = $this->router->buildRoute('search/')->getUrl();
        $this->metas['title'] = $this->_('title');
        
        if ($this->view->query = preg_replace('/[^\d\w -\.]/u', '', urldecode($q))) {
            $this->metas['title'] = $this->_('title_query');
            $this->view->products = $this->dependencyInjectionContainer->get('saq_webservice')->searchWinesByKeyword($this->view->query);
        }
        
        $this->view->from = 's-' . $this->view->query;
    }
}