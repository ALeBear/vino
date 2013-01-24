<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use vino\WinesList;

/**
 * Lists homepage
 */
class Index extends VinoAbstractController
{
    public function prepare()
    {
        $this->view->error = null;
    }
    
    public function execute()
    {
        $this->view->products = array();
        $this->metas['title'] = $this->_('title');

        $this->view->lists = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\WinesList')
            ->findByUser(array('user' => $this->dependencyInjectionContainer->get('user')));
    }
    
    public function post()
    {
        if (!$this->request->get('name')) {
            $this->view->error = 'missing_fields';
            return;
        }

        $list = WinesList::create(
            $this->request->get('name'),
            $this->dependencyInjectionContainer->get('user'));
        $this->dependencyInjectionContainer->get('entity_manager')->persist($list);
        $this->dependencyInjectionContainer->get('entity_manager')->flush();
    }
}