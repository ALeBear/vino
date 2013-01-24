<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use vino\UserWine;

/**
 * Display list contents
 */
class Contents extends VinoAbstractController
{
    public function prepare($id)
    {
        $this->view->error = false;
        $this->view->listId = preg_replace('/[^\d]/', '', $id);
        $this->view->list = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->find('vino\WinesList', $this->view->listId);
    }
    
    public function execute($id, $c = null)
    {
        $this->metas['title'] = $this->_('title', $this->view->list->__toString());
        
        //If a wine code is given, it means we want to remove it from the list
        if ($wineCode = preg_replace('/[^\d]/', '', $c)) {
            $em = $this->dependencyInjectionContainer->get('entity_manager');
            $user = $this->dependencyInjectionContainer->get('user');
            $wine = $em->getRepository('vino\\UserWine')->findOneBy(array('code' => $wineCode, 'user' => $user));
            if ($wine) {
                $this->view->list->removeWine($wine);
                $em->flush();
                $this->view->error = 'removal_done';
            } else {
                $this->view->error = 'weird_error';
            }
        }
    }
}