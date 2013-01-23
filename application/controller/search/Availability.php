<?php

namespace horses\controller\search;

use horses\AbstractController;

/**
 * Availability map
 */
class Availability extends AbstractController
{
    public function execute($c)
    {
        $this->view->code = preg_replace('/[^\d]/', '', $c);
        $this->addJs('https://maps.googleapis.com/maps/api/js?key=AIzaSyBPjbJ1IpzmyvEsu4fihEhcR9Imvl0I6x8&sensor=true');

        $wine = $this->dependencyInjectionContainer
            ->get('saq_webservice')
            ->getWine($this->view->code, $this->dependencyInjectionContainer->get('entity_manager'));
        $this->metas['title'] = urldecode(strip_tags($wine->__toString()));
        $this->view->availabilities = $this->dependencyInjectionContainer->get('saq_webservice')->getAvailabilityByWineCode($this->view->code);
    }
}