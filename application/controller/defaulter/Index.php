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
    
    public function post()
    {
        if (!$this->request->get('listname')) {
            $this->view->error = 'missing_fields';
            return;
        }

        $list = WinesList::create(
            htmlentities($this->request->get('listname')),
            $this->getUser());
        $this->getEntityManager()->persist($list);
        $this->getEntityManager()->flush();
    }
    
    public function prepareView()
    {
        $this->view->searchUrl = $this->router->buildRoute('search/')->getUrl();
        $this->view->logoutUrl = $this->router->buildRoute('auth/login', array('logout' => '1'))->getUrl();
        $this->view->editAccountUrl = $this->router->buildRoute('auth/register')->getUrl();
        $this->view->listUrl = $this->router->buildRoute('lists/contents', array('id' => 'XXXX'))->getUrl();
        $this->view->user = $this->getUser();
        $this->view->lists = $this->getEntityManager()
            ->getRepository('vino\\WinesList')
            ->findByUser(array('user' => $this->getUser()));
        
        $this->metas['title'] = $this->_('welcome', (string) $this->getUser());
        $this->metas['headerButton'] = array(
            'text' => $this->_('settings'),
            'url' => $this->router->buildRoute(sprintf('%s/settings', $this->getModule()))->getUrl(),
            'icon' => 'gear');
    }
}