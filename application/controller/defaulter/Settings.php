<?php

namespace horses\controller\defaulter;

use vino\VinoAbstractController;
use vino\User;

/**
 * Allow a user to view and edit his/her settings
 */
class Settings extends VinoAbstractController
{
    const MIN_LIMIT_AVAIL = 0;
    const MAX_LIMIT_AVAIL = 5;
    const MIN_CLOSE_POS = 1;
    const MAX_CLOSE_POS = 10;
    
    public function prepare()
    {
        $this->view->error = false;
        $this->view->allLocales = $this->getConfig()->get('locale.available');
        $this->view->currentLocale = $this->dependencyInjectionContainer->get('locale')->getLang();
        $this->view->localeUrl = $this->router->buildRoute('defaulter/settings')->getUrl();
        
    }
    
    public function post()
    {
        $closeCount = (int) $this->request->request->get('closePosCount');
        $closeCount > self::MAX_CLOSE_POS && $closeCount = self::MAX_CLOSE_POS;
        $closeCount < self::MIN_CLOSE_POS && $closeCount = self::MIN_CLOSE_POS;
        $this->getUser()->setSetting('closePosCount', $closeCount);
        
        $availabilityDisplayLowerLimit = (int) $this->request->request->get('availabilityDisplayLowerLimit');
        $availabilityDisplayLowerLimit > self::MAX_LIMIT_AVAIL && $availabilityDisplayLowerLimit = self::MAX_LIMIT_AVAIL;
        $availabilityDisplayLowerLimit < self::MIN_LIMIT_AVAIL && $availabilityDisplayLowerLimit = self::MIN_LIMIT_AVAIL;
        $this->getUser()->setSetting('availabilityDisplayLowerLimit', $availabilityDisplayLowerLimit);
        
        $this->getUser()->setSetting('availabilityHideOnline', (bool) $this->request->request->get('hideOnlineAvail'));
        $this->getEntityManager()->persist($this->getUser());
        $this->getEntityManager()->flush();
        $this->view->error = 'settings_saved';
    }
    
    public function prepareView()
    {
        $this->metas['title'] = $this->_('title');
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        
        $this->view->closePosCount = $this->getUser()->getSetting('closePosCount');
        $this->view->limitAvailDisplay = $this->getUser()->getSetting('availabilityDisplayLowerLimit');
        $this->view->hideOnlineAvail = $this->getUser()->getSetting('availabilityHideOnline');
        $this->view->minLimitAvail = self::MIN_LIMIT_AVAIL;
        $this->view->maxLimitAvail = self::MAX_LIMIT_AVAIL;
        $this->view->minClosePos = self::MIN_CLOSE_POS;
        $this->view->maxClosePos = self::MAX_CLOSE_POS;
    }
}