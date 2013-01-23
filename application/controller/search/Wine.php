<?php

namespace horses\controller\search;

use horses\AbstractController;

/**
 * Search homepage
 */
class Wine extends AbstractController
{
    public function execute($c)
    {
        $code = preg_replace('/[^\d]/', '', $c);
        $em = $this->dependencyInjectionContainer->get('entity_manager');
        
        //Try to load from DB, other wise from SAQ (and save in DB)
        $this->view->wine = $this->dependencyInjectionContainer
                ->get('saq_webservice')
                ->getWine($code, $em);
        $this->metas['title'] = $this->_('title', $this->view->wine->getName(), $this->view->wine->getCode());
    }
}