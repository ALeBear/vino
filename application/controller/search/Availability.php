<?php

namespace horses\controller\search;

use vino\VinoAbstractController;

/**
 * Availability map
 */
class Availability extends VinoAbstractController
{
    public function prepareView($c, $f)
    {
        $this->view->backUrl = $this->getBackUrl($f);
        $this->view->code = $c;
        $this->addJs('https://maps.googleapis.com/maps/api/js?key=AIzaSyBPjbJ1IpzmyvEsu4fihEhcR9Imvl0I6x8&sensor=true');
        $wine = $this->getSaqWebservice()->getWine($c);
        $onlineAvail = $this->getUser()->getSetting('availabilityHideOnline')
            ? $this->_('unknown')
            : $this->getSaqWebservice()->getOnlineAvailabilityByWineCode($c);
        $this->metas['title'] = $this->_('title', urldecode(strip_tags($wine->__toString())), $onlineAvail);
        $this->view->availabilities = $this->getSaqWebservice()
            ->getAvailabilityByWineCode($c, $this->getUser()->getSetting('availabilityDisplayLowerLimit'));
    }
}