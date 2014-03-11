<?php

namespace horses\controller\arrival;

use vino\VinoAbstractController;
use vino\saq\ArrivalRepository;
use DateTime;

/**
 * Display arrivals list
 */
class Index extends VinoAbstractController
{

    /**
     * @inheritdoc
     */
    protected function filterMagicParam($name, $value)
    {
        switch ($name) {
            case 'dt':
                return preg_match('/[^(\d){4}-(\d){2}-(\d){2}$]/', $value) ? $value : null;
            default:
                return parent::filterMagicParam($name, $value);
        }
    }

    protected function prepare()
    {
        $this->view->allDates = $this->getEntityManager()->getRepository('vino\\saq\\Arrival')->getAllDates();
    }
    
    protected function execute()
    {
    }
    
    protected function prepareView($dt = null)
    {
        /** @var DateTime $date */
        $date = $dt ? new DateTime($dt) : reset($this->view->allDates);
        $this->view->arrivals = $this->getEntityManager()
            ->getRepository('vino\\saq\\Arrival')
            ->findBy(array('arrivalDate' => $date), array('name' => 'ASC'));
    }
}