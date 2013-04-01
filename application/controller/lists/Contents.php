<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use vino\UserWine;

/**
 * Display list contents
 */
class Contents extends VinoAbstractController
{
    protected $MODE_EDIT = 'edit';
    protected $MODE_VIEW = 'view';
    
    public function prepare($id, $c = null, $d = null, $listname = null, $ef = null)
    {
        $this->view->showAvailabilityFor = null;
        $this->view->availabilities = array();
        $this->view->listId = preg_replace('/[^\d]/', '', $id);
        $this->view->list = $this->getEntityManager()
            ->getRepository('vino\\WinesList')
            ->findOneBy(array('id' => $this->view->listId, 'user' => $this->getUser()));
        
        if (!$this->view->list) {
            //Not your list, buddy (or deleted)
            $this->redirect('/');
        }
        
        $this->view->from = 'l-' . $this->view->listId;
        
        $this->view->error = false;
        
        //Add to favorites if asked for
        if ($ef) {
            if ($favoritePosId = $this->request->query->get('add')) {
                $this->getUser()->addToFavoritePos(preg_replace('/[^\d]/', '', $favoritePosId));
            }
            if ($favoritePosId = $this->request->query->get('rem')) {
                $this->getUser()->removeFromFavoritePos(preg_replace('/[^\d]/', '', $favoritePosId));
            }
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }
        
        //If a list name is given, it means we want to rename it
        if ($listname) {
            $this->getEntityManager()->persist($this->view->list->setName(htmlentities($listname)));
            $this->getEntityManager()->flush();
        }
        
        //If a wine code is given, it means we want to remove it from the list
        if ($wineCode = preg_replace('/[^\d]/', '', $c)) {
            $wine = $this->getWine($wineCode);
            if ($wine) {
                $this->view->list->removeWine($wine);
                $this->getEntityManager()->flush();
                $this->view->error = 'removal_done';
            } else {
                $this->view->error = 'weird_error';
            }
        }
        
        //If a deletion code is given, remove the list
        if ($d) {
            $this->getEntityManager()->remove($this->view->list);
            $this->getEntityManager()->flush();
            $this->redirect('/');
        }
    }
    
    public function execute($id, $m = 'view')
    {
        $this->view->mode = $m == $this->MODE_EDIT ? $m : $this->MODE_VIEW;
        $this->view->wines = array();
        foreach ($this->view->list->getWineIds() as $wineId) {
            $this->view->wines[$wineId] = $this->getWine($wineId);
        }
        usort($this->view->wines, function($el1, $el2) { return $el1->__toString() < $el2->__toString() ? -1 : 1; });
        
        //If a pos id is passed as "a" (availability), we have to calculate it
        if ($a = $this->request->query->get('a')) {
            $this->view->showAvailabilityFor = $a;
            foreach ($this->view->list->getWineIds() as $wineId) {
                if ($a == 'online') {
                    $this->view->availabilities[$wineId] = $this->getSaqWebservice()
                        ->getOnlineAvailabilityByWineCode($wineId);
                } else {
                    foreach ($this->getSaqWebservice()->getAvailabilityByWineCode($wineId) as $avail) {
                        if ($avail->getPos()->getId() == $a) {
                            $this->view->availabilities[$wineId] = $avail->getQuantity();
                            break;
                        }
                    }
                    isset($this->view->availabilities[$wineId]) || $this->view->availabilities[$wineId] = 0;
                }
            }
        }
        
        $oppositeMode = $this->view->mode == $this->MODE_EDIT ? $this->MODE_VIEW : $this->MODE_EDIT;
        $this->metas['headerButton'] = array(
            'text' => $this->_($oppositeMode),
            'url' => $this->router->buildRoute(sprintf('%s/%s', $this->getModule(), $this->getAction()), array('id' => $this->view->listId, 'm' => $oppositeMode))->getUrl(),
            'icon' => '');

        $this->view->favoritePos = $this->getUser()->getFavoritePos();
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        $this->view->favoritesUrl = $this->router->buildRoute('lists/contents', array('ef' => '1', 'id' => $this->view->listId))->getUrl();
        $this->view->currentUrl = $this->router->buildRoute('lists/contents', array('id' => $this->view->listId))->getUrl();
        $this->metas['title'] = $this->_('title', $this->view->list->__toString());
        $this->addJs($this->getConfig()->get('saq.availability.posFile'));
        $this->addJs('/js/calculateNearestPos.js');
    }
}