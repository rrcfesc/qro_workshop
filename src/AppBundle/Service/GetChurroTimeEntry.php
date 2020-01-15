<?php

namespace AppBundle\Service;

use AppBundle\Entity\ChurroTimeEntry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class GetChurroTimeEntry
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return ChurroTimeEntry[]
     * @throws Exception
     */
    public function __invoke()
    {
        return $this->entityManager
                ->getRepository(ChurroTimeEntry::class)
                ->findAllDuringLastWeekOrderedByNewest();
    }

}