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
        parent::prepare();
        $this->view->error = '';
    }
    
    public function execute($logout = null, $forgot = null)
    {
        //Logout if asked to
        if ($logout) {
            $this->dependencyInjectionContainer->get('auth')->removeUserFromSession($this->request->getSession());
        }
        
        $this->metas['title'] = $this->_('login');
        $this->metas['headerButton'] = array(
            'text' => $this->_('register'),
            'url' => $this->router->buildRoute(sprintf('%s/register', $this->getModule()))->getUrl(),
            'icon' => 'add');
        $this->view->email = htmlentities($this->request->get('email'));
        
        //Send forgot password email if asked to
        if ($forgot) {
            $em = $this->dependencyInjectionContainer->get('entity_manager');
            
            /* @var $forgotUser \vino\User */
            $forgotUser = $em
                ->getRepository('vino\\User')
                ->findOneBy(array('email' => $this->view->email));
            if ($forgotUser) {
                $newPass = $forgotUser->resetPassword();
                $em->persist($forgotUser);
                $em->flush();
                mail(
                    $forgotUser->getEmail(),
                    $this->_('email_password_reset_title', $this->_('app_name')),
                    $this->_('email_password_reset_body', $newPass));
            }
            $this->view->error = 'email_sent';
        }
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
            $auth::getPasswordHash($this->request->get('password')));
        if (!$user) {
            $this->view->error = 'user_not_found';
            return;
        }
        $auth->saveUserToSession($user, $this->request->getSession());

        $this->redirect('/');
    }
}