<?php

namespace horses\controller\lists;

use horses\AbstractController;
use vino\UserWine;

/**
 * Adds a wine to a list
 */
class Addwine extends AbstractController
{
    public function prepare($c, $n)
    {
        $this->view->error = false;
        $this->view->code = preg_replace('/[^\d]/', '', $c);
        $this->view->name = urldecode(strip_tags($n));
    }
    
    public function execute()
    {
        $this->metas['title'] = $this->_('title');
        $this->view->lists = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\WinesList')
            ->findByUser(array('user' => $this->dependencyInjectionContainer->get('user')));
    }
    
    public function post()
    {
        $listId = preg_replace('/[^\d]/', '', $this->request->get('list'));
        if (!$listId) {
            $this->view->error = 'missing_fields';
            return;
        }
        
        $em = $this->dependencyInjectionContainer->get('entity_manager');
        $user = $this->dependencyInjectionContainer->get('user');
        
        //Find list
        $list = $em->find('vino\\WinesList', $listId);
        if (!$list || $list->getUser()->getId() != $user->getId()) {
            $this->view->error = 'unknown_list';
            return;
        }

        //Find or create wine
        $wine = $em->getRepository('vino\\UserWine')->findOneBy(array('code' => $this->view->code, 'user' => $user));
        if (!$wine) {
            $saqWine = $this->dependencyInjectionContainer
                ->get('saq_webservice')
                ->getWine($this->view->code, $em);

            $wine = UserWine::create($saqWine, $user);
        }
        
        //Persist
        $list->addWine($wine);
        $em->persist($wine);
        $em->flush();

        $this->redirect('lists/');
    }
}