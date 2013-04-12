<?php

namespace horses\controller\search;

use vino\VinoAbstractController;
use vino\UserNote;

/**
 * Wine description page
 */
class Wine extends VinoAbstractController
{
    protected function prepareView($c, $f = '')
    {
        //Load base objects
        $this->view->wine = $this->getWine($c);
        $this->view->notes = $this->getEntityManager()
            ->getRepository('vino\\UserNote')
            ->findBy(array('wineCode' => $c), array('created_on' => 'DESC'));
        $this->view->lists = $this->getEntityManager()
            ->getRepository('vino\\WinesList')
            ->findByUser(array('user' => $this->getUser()));
        
        //Calculate average appreciation and capture mine
        $total = $count = 0;
        $this->view->myAppreciation = null;
        foreach ($this->view->notes as $note) {
            if ($note->getAppreciation()) {
                $total += $note->getAppreciation();
                $count++;
                $note->getUser()->getId() == $this->getUser()->getId() && $this->view->myAppreciation = $note->getAppreciation();
            }
            $note->getUser()->getId() == $this->getUser()->getId() && $this->view->myNote = $note;
        }
        $this->view->averageAppreciation = $count ? $total / $count : null;
        isset($this->view->myNote) || $this->view->myNote = UserNote::create($c, $this->getUser(), $this->view->wine->getMillesime());
        
        //Load plain values
        $this->metas['title'] = $this->_('title', $this->view->wine->__toString(), $this->view->wine->getCode());
        $this->view->editFormUrl = $this->router->buildRoute('search/wineEditNote', array('c' => $c, 'f' => $f))->getUrl();
        $this->view->backUrl = $this->getBackUrl($f);
        $this->view->from = 'w-' . $c;
        $f && $this->view->from .= '|' . $f;
        $this->view->availabilityUrl = $this->router->buildRoute('search/availability', array('c' => 'XXXX', 'f' =>  $this->view->from))->getUrl();
        $this->view->addToListUrl = $this->router->buildRoute('lists/addwine', array('c' => 'XXXX', 'f' => $this->view->from, 'l' => ''))->getUrl();
    }
}