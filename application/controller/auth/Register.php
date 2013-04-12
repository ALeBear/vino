<?php

namespace horses\controller\auth;

use vino\VinoAbstractController;
use vino\User;
use Doctrine\DBAL\DBALException;
use horses\plugin\auth\Auth;

/**
 * Registers a user
 */
class Register extends VinoAbstractController
{
    /**
     * @var vino\User
     */
    protected $user;
    
    
    protected function prepare()
    {
        $this->view->error = false;
        $this->view->isEdit = $this->dependencyInjectionContainer->has('user');
        $this->view->isEdit && $this->user = $this->getUser();
    }
    
    protected function post()
    {
        if (!$this->request->get('name') || !$this->request->get('email')
            || (!$this->user && (!$this->request->get('password') || !$this->request->get('password2')))) {
            $this->view->error = 'missing_fields';
            return;
        }
        if (($this->user || $this->request->get('password')) && ($this->request->get('password') != $this->request->get('password2'))) {
            $this->view->error = 'password_mismatch';
            return;
        }
        if ($this->user && !$this->user->isPasswordValid($this->request->get('current_password'))) {
            $this->view->error = 'current_password_invalid';
            return;
        }
        
        $name = htmlentities($this->request->request->get('name'));
        $email = htmlentities($this->request->request->get('email'));
        $password = htmlentities($this->request->request->get('password'));

        try {
            if ($this->user) {
                $this->user->updateData($name, $email);
                $password && $this->user->updatePassword($password);
            } else {
                $this->user = User::create($name, $email, $password);
            }
            $this->getEntityManager()->persist($this->user);
            $this->getEntityManager()->flush();
            
            //Send email
            if ($this->user) {
                mail($email, $this->_('update_email_title', $this->_('app_name'), $name), $this->_('update_email_body'));
            } else {
                mail($email, $this->_('email_title', $this->_('app_name'), $name), $this->_('email_body', $email));
            }
        } catch (DBALException $e) {
            $this->view->error = "email_exists";
            return;
        }
        $this->dependencyInjectionContainer->get('auth')->saveUserToSession($this->user, $this->request->getSession());

        $this->redirect('/');
    }
    
    protected function prepareView()
    {
        $this->metas['title'] = $this->view->isEdit ? $this->_('update_title') : $this->_('title');
        $this->view->name = $this->request->request->get('name', $this->user ? $this->user->__toString() : '');
        $this->view->email = $this->request->request->get('email', $this->user ? $this->user->getEmail() : '');
        
        if (!$this->user) {
            $this->metas['headerButton'] = array(
                'text' => $this->_('login'),
                'url' => $this->router->buildRoute(sprintf('%s/login', $this->getModule()))->getUrl(),
                'icon' => '');
        }
    }
}