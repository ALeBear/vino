<?php

namespace vino\saq;

use Doctrine\ORM\EntityRepository;
use DateTime;

/**
 * Doctrine Arrival entity repository
 * @package vino\saq
 */
class ArrivalRepository extends EntityRepository
{
    /**
     * Get All the available arrival dates ordered by date desc
     * @return DateTime[]
     */
    public function getAllDates()
    {
        $result = array();
        foreach ($this->createQueryBuilder('a')
                     ->resetDQLPart('select')
                     ->addSelect('a.arrivalDate')
                     ->addOrderBy('a.arrivalDate', 'DESC')
                     ->addGroupBy('a.arrivalDate')
                     ->getQuery()
                     ->getArrayResult() as $row) {
            $result[] = array_pop($row);
        }

        return $result;
    }

    /**
     * Get All the available arrival dates ordered by date desc
     * @return string[]
     */
    public function getAllColors()
    {
        $result = array();
        foreach ($this->createQueryBuilder('a')
                     ->resetDQLPart('select')
                     ->addSelect('a.color')
                     ->addOrderBy('a.color', 'ASC')
                     ->addGroupBy('a.color')
                     ->getQuery()
                     ->getArrayResult() as $row) {
            $result[] = array_pop($row);
        }

        return $result;
    }

    /**
     * Get All the available arrival dates ordered by date desc
     * @return string[]
     */
    public function getAllCountries()
    {
        $result = array();
        foreach ($this->createQueryBuilder('a')
                     ->resetDQLPart('select')
                     ->addSelect('a.country')
                     ->addOrderBy('a.country', 'ASC')
                     ->addGroupBy('a.country')
                     ->getQuery()
                     ->getArrayResult() as $row) {
            $result[] = array_pop($row);
        }

        return $result;
    }

    /**
     * Find arrivals based on some criterias
     * @param string $text
     * @param DateTime $date
     * @param string $country
     * @param string $color
     * @param string $orderColumn
     * @param string $orderDirection
     * @return Arrival[]
     */
    public function findByCriterias($text = '', DateTime $date = null, $country = null, $color = null, $orderColumn = null, $orderDirection = null)
    {
        $qb = $this->createQueryBuilder('a');
        if ($date) {
            $qb->andWhere($qb->expr()->eq('a.arrivalDate', ':dt'));
            $qb->setParameter('dt', $date->format('Y-m-d'));
        }
        if ($country) {
            $qb->andWhere($qb->expr()->eq('a.country', ':country'));
            $qb->setParameter('country', $country);
        }
        if ($color) {
            $qb->andWhere($qb->expr()->eq('a.color', ':color'));
            $qb->setParameter('color', $color);
        }
        if (strlen($text) > 2) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('a.name', ':name'),
                $qb->expr()->like('a.producer', ':producer')
            ));
            $qb->setParameter('name', '%' . $text . '%');
            $qb->setParameter('producer', '%' . $text . '%');
        }
        if (in_array($orderColumn, array('arrivalDate', 'country', 'color', 'name', 'price', 'region'))) {
            $dir = in_array($orderDirection, array('ASC', 'DESC')) ? $orderDirection : 'ASC';
            $qb->addOrderBy('a.' . $orderColumn, $dir);
        }

        return $qb->getQuery()->getResult();
    }
}