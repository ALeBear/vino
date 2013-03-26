<?php

namespace vino;

use horses\AbstractController;

class VinoAbstractController extends AbstractController
{
    /**
     * Returns a saq\Wine with this code, create one (NOT PERSISTED YET) if not
     * found
     * @param string $code
     * @return saq\Wine
     */
    public function getWine($code)
    {
        $wine = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\saq\\Wine')
            ->findOneBy(array('code' => $code, 'lang' => $this->dependencyInjectionContainer->get('locale')->getLang()));
        $wine || $wine = $this->dependencyInjectionContainer
            ->get('saq_webservice')
            ->getWine($code);
        
        return $wine;
    }
    
    /**
     * Gets a back url from a "from" query string param
     * @param string $from
     * @return string
     */
    public function getBackUrl($from)
    {
        $parts = explode('-', $from, 2);
        if (count($parts) < 2) {
            return '';
        }
        
        switch ($parts[0]) {
            case 's':
                return $this->router->buildRoute('search/', array('q' => $parts[1]))->getUrl();
            default:
                return '';
        }
    }
}
