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
        $this->metas['title'] = $this->_('title');
        
        if ($this->view->query = preg_replace('/[^\d\w -\.]/', '', $q)) {
            $this->metas['title'] = $this->_('title_query', $this->view->query);
            $this->view->products = $this->dependencyInjectionContainer->get('saq_webservice')->searchWinesByKeyword($this->view->query);
        }
    }
}