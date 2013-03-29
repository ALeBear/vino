<?php

namespace horses\controller\defaulter;

use vino\VinoAbstractController;
use vino\WinesList;

/**
 * Homepage
 */
class Index extends VinoAbstractController
{
    public function prepare()
    {
        $this->view->error = null;
    }
    
    public function execute()
    {
        $this->view->searchUrl = $this->router->buildRoute('search/')->getUrl();
        $this->view->logoutUrl = $this->router->buildRoute('auth/login', array('logout' => '1'))->getUrl();
        $this->view->user = $this->dependencyInjectionContainer->get('user');
        $this->view->lists = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\WinesList')
            ->findByUser(array('user' => $this->dependencyInjectionContainer->get('user')));
        
        $this->metas['title'] = $this->_('welcome', (string) $this->dependencyInjectionContainer->get('user'));
        $this->metas['headerButton'] = array(
            'text' => $this->_('settings'),
            'url' => $this->router->buildRoute(sprintf('%s/settings', $this->getModule()))->getUrl(),
            'icon' => 'gear');
    }
    
    public function post()
    {
        if (!$this->request->get('listname')) {
            $this->view->error = 'missing_fields';
            return;
        }

        $list = WinesList::create(
            htmlentities($this->request->get('listname')),
            $this->dependencyInjectionContainer->get('user'));
        $this->dependencyInjectionContainer->get('entity_manager')->persist($list);
        $this->dependencyInjectionContainer->get('entity_manager')->flush();
    }
}