<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Exception;

class ChurroTimeEntryRepository extends EntityRepository
{
    /**
     * @return ChurroTimeEntry[]
     * @throws Exception
     */
    public function findAllDuringLastWeekOrderedByNewest()
    {
        return $this->createQueryBuilder('churro_time_entry')
            ->where('churro_time_entry.startCookingAt > :date')
            ->setParameter('date', new \DateTime('-1 week'))
            ->orderBy('churro_time_entry.startCookingAt', 'DESC')
            ->getQuery()
            ->getResult();

    }

}