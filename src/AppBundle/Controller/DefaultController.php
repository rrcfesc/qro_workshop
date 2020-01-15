<?php

namespace AppBundle\Controller;

use AppBundle\Service\ChurroTimeEntryStatsHelper;
use AppBundle\Service\GetChurroTimeEntry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homepageAction()
    {
        /** @var ChurroTimeEntryStatsHelper $churroHelper */
        $churroHelper = $this->get(ChurroTimeEntryStatsHelper::class);

        $churroStats = $churroHelper->getMostEfficientTypeData();

        return $this->render('AppBundle:Default:homepage.html.twig', array(
            'timeEntries' => $churroStats->getList(),
            'bestType' => $churroStats->getType(),
            'avg' => $churroStats->getAvg(),
        ));
    }
}
