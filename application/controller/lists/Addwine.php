<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use vino\UserWine;

/**
 * Adds a wine to a list
 */
class Addwine extends VinoAbstractController
{
    public function prepare($c)
    {
        $this->view->error = false;
        $this->view->code = preg_replace('/[^\d]/', '', $c);
        
        $this->view->wine = $this->getWine($this->view->code);
    }
    
    public function execute()
    {
        $this->metas['title'] = $this->_('title');
        $this->view->lists = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\WinesList')
            ->findByUser(array('user' => $this->dependencyInjectionContainer->get('user')));
    }
    
    public function post()
    {
        $listId = preg_replace('/[^\d]/', '', $this->request->get('list'));
        if (!$listId) {
            $this->view->error = 'missing_fields';
            return;
        }
        
        $em = $this->dependencyInjectionContainer->get('entity_manager');
        $user = $this->dependencyInjectionContainer->get('user');
        
        //Find list
        $list = $em->find('vino\\WinesList', $listId);
        if (!$list || $list->getUser()->getId() != $user->getId()) {
            $this->view->error = 'unknown_list';
            return;
        }
        
        //Persist
        $this->view->wine->setPersonalData($this->request->get('note'), $this->request->get('appreciation'));
        $list->addWine($this->view->wine);
        $em->persist($this->view->wine);
        $em->flush();

        $this->redirect('lists/');
    }
}