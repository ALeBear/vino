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
        $wine = $this->getWine($code);
        $note = $this->getEntityManager()
            ->getRepository('vino\\UserNote')
            ->findOneBy(array('wineCode' => $code, 'user' => $this->getUser()));
        $note || $note = UserNote::create($code, $this->getUser(), $wine->getMillesime());
        
        $note->setText(htmlentities($this->request->get('note')));
        $note->setAppreciation(preg_replace('/[^\d]/', '', $this->request->get('appreciation')));
        $this->getEntityManager()->persist($note);
        $this->getEntityManager()->flush();

        $this->redirect('search/wine', array('c' => $code, 'f' => $f));
    }
}