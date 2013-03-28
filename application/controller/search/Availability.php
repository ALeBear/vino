<?php

namespace horses\controller\search;

use vino\VinoAbstractController;

/**
 * Availability map
 */
class Availability extends VinoAbstractController
{
    public function execute($c, $f)
    {
        $this->view->backUrl = $this->getBackUrl($f);
        $this->view->code = preg_replace('/[^\d]/', '', $c);
        $this->addJs('https://maps.googleapis.com/maps/api/js?key=AIzaSyBPjbJ1IpzmyvEsu4fihEhcR9Imvl0I6x8&sensor=true');

        $user = $this->dependencyInjectionContainer->get('user');
        $wine = $this->dependencyInjectionContainer
            ->get('saq_webservice')
            ->getWine($this->view->code);
        $onlineAvail = $user->getSetting('availabilityHideOnline')
            ? $this->_('unknown')
            : $this->dependencyInjectionContainer->get('saq_webservice')->getOnlineAvailabilityByWineCode($this->view->code);
        $this->metas['title'] = $this->_('title', urldecode(strip_tags($wine->__toString())), $onlineAvail);
        $this->view->availabilities = $this->dependencyInjectionContainer
            ->get('saq_webservice')
            ->getAvailabilityByWineCode($this->view->code, $user->getSetting('availabilityDisplayLowerLimit'));
    }
}