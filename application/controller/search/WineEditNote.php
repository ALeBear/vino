<?php

namespace horses\controller\search;

use vino\VinoAbstractController;
use vino\UserNote;

/**
 * Wine note edition page
 */
class WineEditNote extends VinoAbstractController
{
    
    /**
     * Does this controller have a view?
     * @return boolean
     */
    public function hasView()
    {
        return false;
    }
    
    protected function post($c, $f = '')
    {
        $note = $this->getEntityManager()
            ->getRepository('vino\\UserNote')
            ->findOneBy(array('wineCode' => $c, 'user' => $this->getUser()));
        
        if ($note && !$this->request->get('note') && !$this->request->get('appreciation')) {
            //Note "reset": delete it
            $this->getEntityManager()->remove($note);
        } else {
            //Note edition
            $note || $note = UserNote::create($c, $this->getUser(), $this->getWine($c)->getMillesime());

            $note->setText(htmlentities($this->request->get('note')));
            $note->setAppreciation(preg_replace('/[^\d]/', '', $this->request->get('appreciation')));
            $this->getEntityManager()->persist($note);
        }
        $this->getEntityManager()->flush();

        $this->redirect('search/wine', array('c' => $c, 'f' => $f));
    }
}