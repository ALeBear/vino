<?php

namespace horses\controller\auth;

use vino\VinoAbstractController;

/**
 * Logs the user in :)
 */
class Login extends VinoAbstractController
{
    protected function prepare()
    {
        $this->view->error = '';
    }
    
    protected function post()
    {
        if (!$this->request->request->get('email') || !$this->request->request->get('password')) {
            $this->view->error = 'missing_fields';
            return;
        }

        /* @var $auth horses\plugin\auth\Auth */
        $auth = $this->dependencyInjectionContainer->get('auth');
        $user = $auth->getUser(
            $this->request->request->get('email'),
            $auth::getPasswordHash($this->request->request->get('password')));
        if (!$user) {
            $this->view->error = 'user_not_found';
            return;
        }
        $auth->saveUserToSession($user, $this->request->getSession());

        $this->redirect('/');
    }
    
    protected function execute($logout = null, $forgot = null)
    {
        //Logout if asked to
        if ($logout) {
            $this->dependencyInjectionContainer->get('auth')->removeUserFromSession($this->request->getSession());
        }
        
        //Send forgot password email if asked to
        $forgot && $this->forgotPassword($this->request->request->get('email'));
    }
    
    protected function prepareView()
    {
        $this->metas['title'] = $this->_('login');
        $this->metas['headerButton'] = array(
            'text' => $this->_('register'),
            'url' => $this->router->buildRoute(sprintf('%s/register', $this->getModule()))->getUrl(),
            'icon' => 'add');
        $this->view->email = htmlentities($this->request->request->get('email'));
    }

    /**
     * Send the "forgot password" email
     * @param string $email
     * @return \horses\controller\auth\Login
     */
    protected function forgotPassword($email)
    {
        /* @var $forgotUser \vino\User */
        $forgotUser = $this->getEntityManager()
            ->getRepository('vino\\User')
            ->findOneBy(array('email' => $email));
        if ($forgotUser) {
            $newPass = $forgotUser->resetPassword();
            $this->getEntityManager()->persist($forgotUser);
            $this->getEntityManager()->flush();
            mail(
                $forgotUser->getEmail(),
                $this->_('email_password_reset_title', $this->_('app_name')),
                $this->_('email_password_reset_body', $newPass));
        }
        $this->view->error = 'email_sent';
        
        return $this;
    }
}