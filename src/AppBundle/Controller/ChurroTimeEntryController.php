<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ChurroTimeEntry;
use AppBundle\Service\ChurroTimeEntryStatsHelper;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChurroTimeEntryController extends Controller
{
    public function listAction()
    {
        $timeEntries = $this->getDoctrine()
            ->getRepository(ChurroTimeEntry::class)
            ->createQueryBuilder('churro_time_entry')
            ->where('churro_time_entry.startCookingAt > :date')
            ->setParameter('date', new \DateTime('-1 week'))
            ->orderBy('churro_time_entry.startCookingAt', 'DESC')
            ->getQuery()
            ->getResult();

        /** @var LoggerInterface $logger */
        $logger = $this->get('logger');
        $logger->debug(sprintf('Liist of entries found %d', count($timeEntries)));

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
        foreach ($timeEntries as $timeEntry) {
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

        /** @var ChurroTimeEntryStatsHelper $churroHelper */
        $churroHelper = $this->get(ChurroTimeEntryStatsHelper::class);

        list($bestType, $avg) = $churroHelper->getMostEfficientTypeData($types);

        return $this->render('AppBundle:ChurroTimeEntry:list.html.twig', [
            'timeEntries' => $timeEntries,
            'bestType' => $bestType,
            'avg' => $avg,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function itemAction(Request $request, $id)
    {
        /** @var ChurroTimeEntry|null $item */
        $item = $this->getDoctrine()->getRepository(ChurroTimeEntry::class)->find($id);

        if ($item === null) {
            throw $this->createNotFoundException(sprintf("No entry found: %s", $id));
        }
        return $this->render('@App/ChurroTimeEntry/item.html.twig', ['item' => $item]);
    }

    /**
     * @param array<int, array<int>>$churroTypes
     *
     * @return array<int, string>
     */
    private function getMostEfficientTypeData(array $churroTypes)
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
