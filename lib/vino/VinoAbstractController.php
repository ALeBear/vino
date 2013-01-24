<?php

namespace vino;

use horses\AbstractController;

class VinoAbstractController extends AbstractController
{
    /**
     * Returns a UserWine with this code, create one (NOT PERSISTED YET) if not
     * found
     * @param string $code
     * @return UserWine
     */
    public function getWine($code)
    {
        $user = $this->dependencyInjectionContainer->get('user');
        $wine = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\UserWine')
            ->findOneBy(array('code' => $code, 'user' => $user));
        $wine || $wine = UserWine::create(
            $this->dependencyInjectionContainer
                ->get('saq_webservice')
                ->getWine($code),
            $user);
        
        return $wine;
    }
}
