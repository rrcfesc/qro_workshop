<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ChurroTimeEntry;
use AppBundle\Service\ChurroTimeEntryStatsHelper;
use AppBundle\Service\GetChurroTimeEntry;
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
        /** @var ChurroTimeEntryStatsHelper $churroHelper */
        $churroHelper = $this->get(ChurroTimeEntryStatsHelper::class);

        $churroStats = $churroHelper->getMostEfficientTypeData();

        /** @var LoggerInterface $logger */
        $logger = $this->get('logger');
        $logger->debug(sprintf('Liist of entries found %d', count($churroStats->getList())));



        return $this->render('AppBundle:ChurroTimeEntry:list.html.twig', [
            'timeEntries' => $churroStats->getList(),
            'bestType' => $churroStats->getType(),
            'avg' => $churroStats->getAvg(),
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
