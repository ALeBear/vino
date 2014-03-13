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
            case 'orderBy':
                return preg_match('/^[a-z]+-(ASC|DESC)$/', $value) ? $value : '';
            default:
                return parent::filterMagicParam($name, $value);
        }
    }

    protected function prepare()
    {
        $this->view->allDates = $this->getEntityManager()->getRepository('vino\\saq\\Arrival')->getAllDates();
        $this->view->allCountries = $this->getEntityManager()->getRepository('vino\\saq\\Arrival')->getAllCountries();
        $this->view->allColors = $this->getEntityManager()->getRepository('vino\\saq\\Arrival')->getAllColors();
    }
    
    protected function execute($search = null, $dt = null, $country = null, $color = null, $orderBy = null)
    {
        if ($dt || strlen($search) > 2 || $country || $color) {
            if ($orderBy) {
                list($orderColumn, $orderDirection) = explode('-', $orderBy);
            } else {
                $orderColumn = 'name';
                $orderDirection = 'ASC';
            }
            $this->view->arrivals = $this->getEntityManager()
                ->getRepository('vino\\saq\\Arrival')
                ->findByCriterias($search, $dt ? new DateTime($dt) : null, $country, $color, $orderColumn, $orderDirection);
        } else {
            $this->view->arrivals = null;
        }
    }
    
    protected function prepareView($search = null, $dt = null, $country = null, $color = null, $orderBy = null)
    {
        $this->metas['title'] = $this->_('title');
        $this->view->currentDate = $dt ? new DateTime($dt) : null;
        $this->view->currentCountry = $country;
        $this->view->currentColor = $color;
        $this->view->currentSearch = $search;
        $this->view->currentOrderBy = $orderBy ? $orderBy : 'name-ASC';
        $this->view->formUrl = $this->router->buildRoute('arrival/')->getUrl();
    }
}