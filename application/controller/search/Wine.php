<?php

namespace horses\controller\search;

use vino\VinoAbstractController;

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
        foreach ($this->view->notes as $note) {
            if ($note->getAppreciation()) {
                $total += $note->getAppreciation();
                $count++;
                $note->getUser()->getId() == $this->dependencyInjectionContainer->get('user')->getId() && $this->view->myAppreciation = $note->getAppreciation();
            }
        }
        $this->view->averageAppreciation = $count ? $total / $count : null;
        
        $this->metas['title'] = $this->_('title', $this->view->wine->__toString(), $this->view->wine->getCode());
    }
}