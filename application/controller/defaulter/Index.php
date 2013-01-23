<?php

namespace horses\controller\defaulter;

use horses\AbstractController;

/**
 * Homepage
 */
class Index extends AbstractController
{
    public function execute()
    {
        $this->view->searchUrl = $this->router->buildRoute('search/')->getUrl();
        $this->view->listsUrl = $this->router->buildRoute('lists/')->getUrl();
        $this->view->logoutUrl = $this->router->buildRoute('auth/login')->getUrl();
        $this->view->user = $this->dependencyInjectionContainer->get('user');
        $this->metas['title'] = $this->_('welcome', (string) $this->dependencyInjectionContainer->get('user'));
    }
}