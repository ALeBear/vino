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
    
    
    /**
     * Used to filter query (and path) parameters before passing them to magic
     * methods
     * @param string $name
     * @param mixed $value
     * @return mixed the filtered out value
     */
    protected function filterMagicParam($name, $value)
    {
        switch ($name) {
            case 'addf':
            case 'remf':
            case 'l':
                return preg_replace('/[^\d]/', '', $value);
            case 'a':
                return $value == 'online' ? $value : preg_replace('/[^\d]/', '', $value);
            case 'm':
                return $value == $this->MODE_EDIT ? $value : $this->MODE_VIEW;
            default:
                return parent::filterMagicParam($name, $value);
        }
    }
    
    public function prepare($id, $a = null)
    {
        //Build list and redirect if not found
        $this->view->list = $this->getEntityManager()
            ->getRepository('vino\\WinesList')
            ->findOneBy(array('id' => $id, 'user' => $this->getUser()));
        if (!$this->view->list) {
            //Not your list, buddy (or deleted)
            $this->redirect('/');
        }
        
        $this->view->error = false;
        $this->view->showAvailabilityFor = $a;
        $this->view->showAvailabilityForPos = null;
        $this->view->availabilities = array();
    }
    
    public function execute($c = null, $d = null, $listname = null, $addf = null, $remf = null, $a = null)
    {
        //Manage favorites if asked for
        $addf && $this->getUser()->addToFavoritePos($addf);
        $remf && $this->getUser()->removeFromFavoritePos($remf);
        if ($addf || $remf) {
            $this->getEntityManager()->persist($this->getUser());
            $this->getEntityManager()->flush();
        }
        
        //If a list name is given, it means we want to rename it
        if ($listname) {
            $this->getEntityManager()->persist($this->view->list->setName(htmlentities($listname)));
            $this->getEntityManager()->flush();
        }
        
        //If a wine code is given, it means we want to remove it from the list
        if ($c) {
            $wine = $this->getWine($c);
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
        
        //If a pos id is passed as "a" (availability), we have to calculate it
        if ($a) {
            $this->view->showAvailabilityFor = $a;
            $this->view->showAvailabilityForPos = $this->getEntityManager()
                ->getRepository('vino\\saq\\Pos')
                ->find($a);
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
    }
    
    public function prepareView($id, $m = 'view')
    {
        $this->view->wines = array();
        foreach ($this->view->list->getWineIds() as $wineId) {
            $this->view->wines[$wineId] = $this->getWine($wineId);
        }
        usort($this->view->wines, function($el1, $el2) { return $el1->__toString() < $el2->__toString() ? -1 : 1; });
        
        $this->view->mode = $m;
        $oppositeMode = $m == $this->MODE_EDIT ? $this->MODE_VIEW : $this->MODE_EDIT;
        $this->metas['title'] = $this->_('title', $this->view->list->__toString());
        $this->metas['headerButton'] = array(
            'text' => $this->_($oppositeMode),
            'url' => $this->router->buildRoute(sprintf('%s/%s', $this->getModule(), $this->getAction()), array('id' => $id, 'm' => $oppositeMode))->getUrl(),
            'icon' => '');
        $this->view->from = 'l-' . $id;
        $this->view->favoritePos = array();
        foreach ($this->getUser()->getFavoritePosIds() as $favPosId) {
            $this->view->favoritePos[$favPosId] = $this->getEntityManager()
                ->getRepository('vino\\saq\\Pos')
                ->find($favPosId);
        }
        $this->view->backUrl = $this->router->buildRoute('/')->getUrl();
        $this->view->currentUrl = $this->router->buildRoute('lists/contents', array('id' => $id))->getUrl();
        $this->view->deleteListUrl = $this->router->buildRoute('lists/contents', array('id' => $id, 'd' => 1))->getUrl();
        $this->view->getClosestPosUrl = $this->router->buildRoute('lists/closestPos')->getUrl();
        $this->view->favoritesAddUrl = $this->router->buildRoute('lists/favoritePos', array('f' => $this->view->from, 'action' => 'a', 'id' => 'XXXX'))->getUrl();
        $this->view->favoritesRemoveUrl = $this->router->buildRoute('lists/favoritePos', array('f' => $this->view->from, 'action' => 'r', 'id' => 'XXXX'))->getUrl();
    }
}