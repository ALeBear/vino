<?php

namespace horses\controller\defaulter;

use vino\VinoAbstractController;
use vino\User;

/**
 * Allow a user to view and edit his/her settings
 */
class Settings extends VinoAbstractController
{
    /**
     * @var vino\User
     */
    protected $user;
    
    
    public function prepare()
    {
        $this->user = $this->dependencyInjectionContainer->get('user');
        $this->view->error = false;
    }
    
    public function execute()
    {
        $this->metas['title'] = $this->_('title');
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        
        $this->view->limitAvailDisplay = $this->user->getSetting('availabilityDisplayLowerLimit', 0);
        $this->view->hideOnlineAvail = $this->user->getSetting('availabilityHideOnline', false);
    }
    
    public function post()
    {
        $this->user->setSetting('availabilityDisplayLowerLimit', preg_replace('/[^\d]/', '', $this->request->get('limitAvailDisplay')));
        $this->user->setSetting('availabilityHideOnline', (int) $this->request->get('hideOnlineAvail'));
        $this->dependencyInjectionContainer->get('entity_manager')->persist($this->user);
        $this->dependencyInjectionContainer->get('entity_manager')->flush();
        $this->view->error = 'settings_saved';
    }
}