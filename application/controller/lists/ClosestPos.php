<?php

namespace horses\controller\lists;

use vino\VinoAbstractController;
use vino\saq\Pos;

/**
 * Get the HTML to fill the select about the X closest pos
 */
class ClosestPos extends VinoAbstractController
{
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
            case 'lat':
            case 'long':
                return preg_replace('/[^-\d\.]/', '', $value);
            default:
                return parent::filterMagicParam($name, $value);
        }
    }
    
    public function execute($lat, $long)
    {
        $this->view->closest = array();
        foreach ($this->getEntityManager()->getRepository('vino\\saq\\Pos')->findAll() as $pos) {
            $dist = $pos->getDistanceTo($lat, $long);
            if (count($this->view->closest) < $this->getUser()->getSetting('closePosCount')) {
                $this->view->closest[] = array(
                    'id' => $pos->getId(),
                    'name' => $pos->__toString(),
                    'dist' => $dist);
            } else {
                foreach ($this->view->closest as $index => $aClosePos) {
                    if ($dist < $aClosePos['dist']) {
                        $this->view->closest[$index] = array(
                            'id' => $pos->getId(),
                            'name' => $pos->__toString(),
                            'dist' => $dist);
                        break;
                    }
                }
            }
        }
    }
}