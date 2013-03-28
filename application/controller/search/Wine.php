<?php

namespace horses\controller\search;

use vino\VinoAbstractController;
use vino\UserNote;

/**
 * Wine description page
 */
class Wine extends VinoAbstractController
{
    public function execute($c, $f = '')
    {
        $this->view->backUrl = $this->getBackUrl($f);
        
        $code = preg_replace('/[^\d]/', '', $c);
        
        $this->view->wine = $this->getWine($code);
        
        $this->view->notes = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\UserNote')
            ->findBy(array('wineCode' => $code), array('created_on' => 'DESC'));
        $total = 0;
        $count = 0;
        $this->view->myAppreciation = null;
        $user = $this->dependencyInjectionContainer->get('user');
        foreach ($this->view->notes as $note) {
            if ($note->getAppreciation()) {
                $total += $note->getAppreciation();
                $count++;
                $note->getUser()->getId() == $user->getId() && $this->view->myAppreciation = $note->getAppreciation();
            }
            $note->getUser()->getId() == $user->getId() && $this->view->myNote = $note;
        }
        $this->view->averageAppreciation = $count ? $total / $count : null;
        isset($this->view->myNote) || $this->view->myNote = UserNote::create($code, $user, $this->view->wine->getMillesime());
        
        $this->view->lists = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\WinesList')
            ->findByUser(array('user' => $this->dependencyInjectionContainer->get('user')));
        
        $this->metas['title'] = $this->_('title', $this->view->wine->__toString(), $this->view->wine->getCode());
        
        $this->view->editFormUrl = $this->router->buildRoute('search/wineEditNote', array('c' => $code, 'f' => $f))->getUrl();
        
        $this->view->from = 'w-' . $code;
        $f && $this->view->from .= '|' . $f;
    }
}