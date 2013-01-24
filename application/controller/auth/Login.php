<?php

namespace horses\controller\auth;

use vino\VinoAbstractController;

/**
 * Logs the user in :)
 */
class Login extends VinoAbstractController
{
    public function prepare()
    {
        $this->view->error = '';
    }
    
    public function execute()
    {
        $this->metas['title'] = $this->_('login');
        $this->metas['headerButton'] = array(
            'text' => $this->_('register'),
            'url' => $this->router->buildRoute(sprintf('%s/register', $this->getModule()))->getUrl(),
            'icon' => 'add');
        $this->view->email = $this->request->get('email');
        $this->view->password = $this->request->get('password');
    }
    
    public function post()
    {
        if (!$this->request->get('email') || !$this->request->get('password')) {
            $this->view->error = 'missing_fields';
            return;
        }

        /* @var $auth horses\plugin\auth\Auth */
        $auth = $this->dependencyInjectionContainer->get('auth');
        $user = $auth->getUser(
            $this->request->get('email'),
            $auth->getPasswordHash($this->request->get('password')));
        if (!$user) {
            $this->view->error = 'user_not_found';
            return;
        }
        $auth->saveUserToSession($user, $this->request->getSession());

        $this->redirect('/');
    }
}