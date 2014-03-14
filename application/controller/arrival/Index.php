<?php

namespace horses\controller\arrival;

use vino\VinoAbstractController;
use DateTime;
use vino\saq\ArrivalWatchlist;

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
    
    protected function execute($seew = null, $search = null, $dt = null, $country = null, $color = null, $orderBy = null, $action = null)
    {
        $this->view->hasUser = (bool) $this->getUser();
        if ($this->view->hasUser) {
            $watchlist = $this->getEntityManager()->getRepository('vino\\saq\\ArrivalWatchlist')->findOneBy(array('user' => $this->getUser()));
            if (!$watchlist) {
                $watchlist = ArrivalWatchlist::create($this->getUser());
                $this->getEntityManager()->persist($watchlist);
                $this->getEntityManager()->flush();
            }
            $this->view->watchlistIds = $watchlist->getArrivalIds();
        }

        //Manage add/remove to watchlist
        if ($action == 'atw') {
            foreach ($this->request->request->getIterator() as $name => $value) {
                if (preg_match('/^watchlist-add-(?P<id>[0-9]+)$/', $name, $matches)) {
                    $watchlist->addRemoveArrival($this->getEntityManager()->getRepository('vino\\saq\\Arrival')->findOneById($matches['id']));
                }

            }
            $this->getEntityManager()->persist($watchlist);
            $this->getEntityManager()->flush();
            $this->view->watchlistIds = $watchlist->getArrivalIds();
        }

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
        } elseif ($seew && $this->view->hasUser) {
            $this->view->arrivals = $this->getEntityManager()
                ->getRepository('vino\\saq\\Arrival')
                ->findById($watchlist->getArrivalIds());
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
        if ($this->view->hasUser) {
            $this->view->detailsUrl = $this->router->buildRoute('search/wine', array('c' => 'SAQCODE'))->getUrl();
            $this->view->watchlistUrl = $this->router->buildRoute('arrival/', array('seew' => '1'))->getUrl();
        }
    }
}