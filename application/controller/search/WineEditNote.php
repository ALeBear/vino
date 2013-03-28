<?php

namespace horses\controller\search;

use vino\VinoAbstractController;
use vino\UserNote;

/**
 * Wine note edition page
 */
class WineEditNote extends VinoAbstractController
{
    public function execute()
    {
    }
    
    public function post($c, $f = '')
    {
        $code = preg_replace('/[^\d]/', '', $c);
        $user = $this->dependencyInjectionContainer->get('user');
        $wine = $this->getWine($code);
        $note = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\UserNote')
            ->findOneBy(array('wineCode' => $code, 'user' => $user));
        $note || $note = UserNote::create($code, $user, $wine->getMillesime());
        
        $em = $this->dependencyInjectionContainer->get('entity_manager');
        $note->setText(htmlentities($this->request->get('note')));
        $note->setAppreciation(preg_replace('/[^\d]/', '', $this->request->get('appreciation')));
        $em->persist($note);
        $em->flush();

        $this->redirect('search/wine', array('c' => $code, 'f' => $f));
    }
}