<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use vino\UserWine;

/**
 * Display list contents
 */
class Contents extends VinoAbstractController
{
    const MODE_EDIT = 'edit';
    const MODE_VIEW = 'view';
    
    
    public function prepare($id, $c = null)
    {
        $this->view->listId = preg_replace('/[^\d]/', '', $id);
        $this->view->list = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->find('vino\WinesList', $this->view->listId);
        
        //If a wine code is given, it means we want to remove it from the list
        $this->view->error = false;
        if ($wineCode = preg_replace('/[^\d]/', '', $c)) {
            $em = $this->dependencyInjectionContainer->get('entity_manager');
            $user = $this->dependencyInjectionContainer->get('user');
            $wine = $this->getWine($wineCode);
            if ($wine) {
                $this->view->list->removeWine($wine);
                $em->flush();
                $this->view->error = 'removal_done';
            } else {
                $this->view->error = 'weird_error';
            }
        }
    }
    
    public function execute($id, $m = self::MODE_VIEW)
    {
        $this->view->mode = $m == self::MODE_EDIT ? $m : self::MODE_VIEW;
        $this->view->wines = array();
        foreach ($this->view->list->getWineIds() as $wineId) {
            $this->view->wines[$wineId] = $this->getWine($wineId);
        }
        usort($this->view->wines, function($el1, $el2) { return $el1->__toString() < $el2->__toString() ? -1 : 1; });
        
        $oppositeMode = $this->view->mode == self::MODE_EDIT ? self::MODE_VIEW : self::MODE_EDIT;
        $this->metas['headerButton'] = array(
            'text' => $this->_($oppositeMode),
            'url' => $this->router->buildRoute(sprintf('%s/%s', $this->getModule(), $this->getAction()), array('id' => $this->view->listId, 'm' => $oppositeMode))->getUrl(),
            'icon' => '');
        
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        $this->metas['title'] = $this->_('title', $this->view->list->__toString());
    }
}