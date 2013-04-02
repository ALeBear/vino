<?php

namespace horses\controller\defaulter;

use vino\VinoAbstractController;
use vino\User;

/**
 * Allow a user to view and edit his/her settings
 */
class Settings extends VinoAbstractController
{
    public function prepare()
    {
        $this->view->error = false;
        $this->view->allLocales = $this->getConfig()->get('locale.available');
        $this->view->currentLocale = $this->dependencyInjectionContainer->get('locale')->getLang();
        $this->view->localeUrl = $this->router->buildRoute('defaulter/settings')->getUrl();
    }
    
    public function post()
    {
        $this->getUser()->setSetting('availabilityDisplayLowerLimit', preg_replace('/[^\d]/', '', $this->request->request->get('limitAvailDisplay')));
        $this->getUser()->setSetting('availabilityHideOnline', (int) $this->request->request->get('hideOnlineAvail'));
        $this->getEntityManager()->persist($this->getUser());
        $this->getEntityManager()->flush();
        $this->view->error = 'settings_saved';
    }
    
    public function prepareView()
    {
        $this->metas['title'] = $this->_('title');
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        
        $this->view->limitAvailDisplay = $this->getUser()->getSetting('availabilityDisplayLowerLimit', 0);
        $this->view->hideOnlineAvail = $this->getUser()->getSetting('availabilityHideOnline', false);
    }
}