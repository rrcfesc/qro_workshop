<?php

namespace AppBundle\Service;

class ChurroTimeEntryStatsHelper
{

    /**
     * @param array $churroTypes
     * @return array
     */
    public function getMostEfficientTypeData(array $churroTypes)
    {
        return self::__invoke($churroTypes);
    }

    /**
     * @param array $churroTypes
     * @return array
     */
    static public function __invoke(array $churroTypes)
    {
        $bestType = null;
        $avg = 0;
        foreach ($churroTypes as $type => $data) {
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

        return [$bestType, $avg];
    }
}