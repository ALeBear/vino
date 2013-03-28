<?php

namespace horses\controller\auth;

use vino\VinoAbstractController;
use vino\User;
use Doctrine\DBAL\DBALException;

/**
 * Registers a user
 */
class Register extends VinoAbstractController
{
    public function prepare()
    {
        parent::prepare();
        $this->view->error = false;
    }
    
    public function execute()
    {
        $this->metas['title'] = $this->_('title');
        $this->view->name = $this->request->get('name');
        $this->view->email = $this->request->get('email');
        
        $this->metas['headerButton'] = array(
            'text' => $this->_('login'),
            'url' => $this->router->buildRoute(sprintf('%s/login', $this->getModule()))->getUrl(),
            'icon' => '');
    }
    
    public function post()
    {
        if (!$this->request->get('name') || !$this->request->get('email') || !$this->request->get('password') || !$this->request->get('password2')) {
            $this->view->error = 'missing_fields';
            return;
        }
        if ($this->request->get('password') != $this->request->get('password2')) {
            $this->view->error = 'password_mismatch';
            return;
        }
        $name = htmlentities($this->request->get('name'));
        $email = htmlentities($this->request->get('email'));

        /* @var $auth horses\plugin\auth\Auth */
        $auth = $this->dependencyInjectionContainer->get('auth');

        try {
            $user = User::create($name, $email, $auth::getPasswordHash($this->request->get('password')));
            $this->dependencyInjectionContainer->get('entity_manager')->persist($user);
            $this->dependencyInjectionContainer->get('entity_manager')->flush();
            
            //Send email
            mail($email, $this->_('email_title', $this->_('app_name'), $name), $this->_('email_body', $email));
        } catch (DBALException $e) {
            $this->view->error = "email_exists";
            return;
        }
        $auth->saveUserToSession($user, $this->request->getSession());

        $this->redirect('/');
    }
}