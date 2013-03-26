<?php

namespace horses\controller\search;

use vino\VinoAbstractController;
use vino\UserNote;

/**
 * Wine note edition page
 */
class WineEditNote extends VinoAbstractController
{
    public function prepare($c)
    {
        $this->view->code = preg_replace('/[^\d]/', '', $c);
        $user = $this->dependencyInjectionContainer->get('user');
        $this->view->wine = $this->getWine($this->view->code);
        $this->view->note = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\UserNote')
            ->findOneBy(array('wineCode' => $this->view->code, 'user' => $user));
        $this->view->note || $this->view->note = UserNote::create($this->view->code, $user, $this->view->wine->getMillesime());
    }
    
    public function execute()
    {
        $this->metas['title'] = $this->_('title', $this->view->wine->__toString());
    }
    
    public function post()
    {
        $em = $this->dependencyInjectionContainer->get('entity_manager');
        
        //Persist
        $this->view->note->setText($this->request->get('note'));
        $this->view->note->setAppreciation($this->request->get('appreciation'));
        $em->persist($this->view->note);
        $em->flush();

        $this->redirect('search/wine', array('c' => $this->view->code));
    }
}