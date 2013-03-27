<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use InvalidArgumentException;

/**
 * Adds a wine to a list
 */
class Addwine extends VinoAbstractController
{
    public function execute($l, $c, $f)
    {
        $listId = preg_replace('/[^\d]/', '', $l);
        $list = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\WinesList')
            ->findOneBy(array('user' => $this->dependencyInjectionContainer->get('user'), 'id' => $listId));
        if (!$list) {
            throw new InvalidArgumentException(sprintf('Invalid list id: %s', $listId));
        }
        
        $wineCode = preg_replace('/[^\d]/', '', $c);
        $wine = $this->getWine($wineCode);
        if (!$wine) {
            throw new InvalidArgumentException(sprintf('Invalid wine code: %s', $wineCode));
        }
        
        $list->addWine($wine);
        $em = $this->dependencyInjectionContainer->get('entity_manager');
        $em->persist($list);
        $em->flush();
        
        $this->router->redirectExternal($this->getBackUrl($f));
    }
}