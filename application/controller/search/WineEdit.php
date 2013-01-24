<?php

namespace horses\controller\search;

use vino\VinoAbstractController;

/**
 * Wine personal infos edition page
 */
class WineEdit extends VinoAbstractController
{
    public function prepare($c)
    {
        $this->view->code = preg_replace('/[^\d]/', '', $c);
        $this->view->wine = $this->getWine($this->view->code);
    }
    
    public function execute()
    {
        $this->metas['title'] = $this->_('title', $this->view->wine->__toString());
    }
    public function post()
    {
        $em = $this->dependencyInjectionContainer->get('entity_manager');
        
        //Persist
        $this->view->wine->setPersonalData($this->request->get('note'), $this->request->get('appreciation'));
        $em->persist($this->view->wine);
        $em->flush();

        $this->redirect('search/wine', array('c' => $this->view->code));
    }
}