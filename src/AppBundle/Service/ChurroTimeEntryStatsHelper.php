<?php

namespace AppBundle\Service;

use AppBundle\Entity\ChurroTimeEntry;
use AppBundle\Model\ChurroTypeStats;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ChurroTimeEntryStatsHelper
{
    /** @var GetChurroTimeEntry  */
    private $churroTimeEntry;

    public function __construct(GetChurroTimeEntry $churroTimeEntry)
    {
        $this->churroTimeEntry = $churroTimeEntry;
    }

    /**
     * @return ChurroTypeStats
     * @throws Exception
     */
    public function getMostEfficientTypeData()
    {
        return $this->__invoke($this->churroTimeEntry->__invoke());
    }

    /**
     * @param ChurroTimeEntry[] $timeEntries
     * @return ChurroTypeStats
     * @throws Exception
     */
     public function __invoke(array $timeEntries)
    {
        $useFilter = true;
        $today = new \DateTime('now');
        if ($today->format('n') <= 6) {
            // don't filter if today is January-June
            $useFilter = false;
        } else {
            if (($today->format('j') === 30 || $today->format('j') === 31)
                && $today->format('n') !== 10) {
                // don't use filter if today is 30th/31st of July-December
                // except for October - always use the filter in October
                $useFilter = false;
            }
        }

        $types = [];
        /** @var ChurroTimeEntry $timeEntry */
        foreach ($timeEntries as $timeEntry) {
            if ($timeEntry->getBakedBy()->getUsername() === 'jwage') {
                continue;
            }
            if ($useFilter && $timeEntry->getStartCookingAt()->format('H') < 6) {
                // skip
            } else {
                if ($useFilter && $timeEntry->getStartCookingAt()->format('H') >= 22) {
                    // skip
                } else {
                    if (isset($types[$timeEntry->getType()])) {
                        $types[$timeEntry->getType()][] = $timeEntry->getQuantityMade();
                    } else {
                        $types[$timeEntry->getType()] = [];
                        $types[$timeEntry->getType()][] = $timeEntry->getQuantityMade();
                    }
                }
            }
        }
        $bestType = null;
        $avg = 0;
        foreach ($types as $type => $data) {
            $total = 0;
            foreach ($data as $quantity) {
                $total += $quantity;
            }

            $thisAverage = $total / count($data);
            if ($thisAverage > $avg) {
                $avg = $thisAverage;
                $bestType = $type;
            }
        }

        return new ChurroTypeStats($bestType, $avg, $timeEntries);
    }
}