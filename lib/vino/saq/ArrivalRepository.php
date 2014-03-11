<?php

namespace vino\saq;

use Doctrine\ORM\EntityRepository;

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
}