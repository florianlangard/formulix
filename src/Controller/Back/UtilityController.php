<?php

namespace App\Controller\Back;

use App\Repository\EventRepository;
use App\Repository\PredictionRepository;
use App\Repository\ResultRepository;
use App\Repository\ScoreRepository;
use App\Service\DataImporter;
use App\Service\FOneApi;
use App\Service\PodiumBuilder;
use App\Service\ScoreCalculator;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilityController extends AbstractController
{
    /**
     * @Route("/back/utility", name="back_utility")
     */
    public function index(
        EventRepository $eventRepository, 
        ResultRepository $resultRepository, 
        PredictionRepository $predictionRepository, 
        ScoreRepository $scoreRepository): Response
    {
        $toUpdate = true;
        $toCalculate = true;
        $toCalculateRanking = true;

        $date = new DateTime('now', new DateTimeZone('UTC'));
        $lastEvent = $eventRepository->findLastEvent($date);
        $ongoingEvent = $eventRepository->findOngoingEvent($date);
        $lastResult = $resultRepository->findOneBy([], ['id' => 'DESC']);
        
        $checkPredictions = $predictionRepository->findOneBy(['event' => $lastResult->getEvent()]);
        $checkRankings = $scoreRepository->findOneBy(['lastEvent' => $lastResult->getEvent()]);
        
        if ($checkPredictions === null) {
            $toCalculate = false;
        }
        if ($checkPredictions !== null && $checkPredictions->getScore() !== null) {
            $toCalculate = false;
        }
        
        if ($lastEvent[0]->getName() === $lastResult->getEvent()->getName() && ($lastResult->getPole() !== null && $lastResult->getFinishedFirst() !== null)) {
            $toUpdate = false;
        }
        if ($checkRankings !== null) {
            if ($checkRankings->getLastEvent() === $lastEvent[0]) {
                $toCalculateRanking = false;
            }
        }
        return $this->render('back/utility/index.html.twig', [
            'lastEvent' => $lastEvent,
            'ongoingEvent' => $ongoingEvent,
            'lastResult' => $lastResult,
            'toUpdate' => $toUpdate,
            'toCalculate' => $toCalculate,
            'toCalculateRanking' => $toCalculateRanking,
        ]);
    }

    /**
     * @Route("/back/utility/get_result", name="back_utility_get_result")
     */
    public function getLastResult(EventRepository $eventRepository, ResultRepository $resultRepository, DataImporter $dataImporter): Response
    {
        $date = new DateTime('now', new DateTimeZone('UTC'));
        $lastEvent = $eventRepository->findLastEvent($date);
        $ongoingEvent = $eventRepository->findOngoingEvent($date);
        if ($ongoingEvent !== null) {
            $qualifyingData = $dataImporter->importQualifyingResults($ongoingEvent->getSeason(), $ongoingEvent->getRound());
        } else {
            $qualifyingData = $dataImporter->importQualifyingResults($lastEvent[0]->getSeason(), $lastEvent[0]->getRound());
        }
        if ($qualifyingData === null) {
            $this->addFlash('error', 'Impossible de r??cup??rer les r??sultats (Qualifs), donn??es indisponibles');
            return $this->redirectToRoute('back_utility');
        } else {
            $this->addFlash('success', 'R??cup??ration des r??sultats (Qualifs) effectu??e');
        }

        $raceData = $dataImporter->importRaceResults($lastEvent[0]->getSeason(), $lastEvent[0]->getRound());
        if ($raceData === null) {
            $this->addFlash('error', 'Impossible de r??cup??rer les r??sultats (Course), donn??es indisponibles');
            return $this->redirectToRoute('back_utility');
        } else {
            $this->addFlash('success', 'R??cup??ration des r??sultats (Course) effectu??e');
        }

        return $this->redirectToRoute('back_utility');
    }

    /**
     * @Route("/back/utility/calculate_scores", name="back_utility_calculate_scores")
     */
    public function calculateScores(ScoreCalculator $scoreCalculator, EventRepository $eventRepository, PodiumBuilder $podiumBuilder): Response
    {
        
        $done = 'calculated';
        $event = $eventRepository->findOneBy(['id' => $eventRepository->findLastEvent(new DateTime('now', new DateTimeZone('UTC')))]);
        
        $calculateQualifyingScore = $scoreCalculator->calculateQualifyingScore($event);
        if ($calculateQualifyingScore === false) {
            $this->addFlash('error', 'Impossible de calculer ces scores (Qualifs), r??sultats indisponibles');
            return $this->redirectToRoute('back_utility');
        } else {
            $this->addFlash('success', 'calcul des r??sultats (Qualifs) effectu??');
            $podiumBuilder->createQualifyingPodium($event);
            $this->addFlash('success', 'Podium pour '. $event->getName() .' (qualifications) cr??e');
        }

        $calculateRaceScore = $scoreCalculator->calculateRaceScore($event);
        if ($calculateRaceScore === false) {
            $this->addFlash('error', 'Impossible de calculer les scores (Course), r??sultats indisponibles');
            return $this->redirectToRoute('back_utility');
        } else {
            $this->addFlash('success', 'calcul des r??sultats (Course) effectu??');
            $podiumBuilder->createRacePodium($event);
            $this->addFlash('success', 'Podium pour '. $event->getName() .' (course) cr??e');
        }

        $scoreCalculator->calculateGlobalEventScore($event);
        $this->addFlash('success', 'calcul des scores globaux effectu??');
        $podiumBuilder->createGlobalEventPodium($event);
        $this->addFlash('success', 'Podium pour '. $event->getName() .' (Global) cr??e');

        return $this->redirectToRoute('back_utility', ['status' => $done]);
    }

    /**
     * @Route("/back/utility/calculate_rankings", name="back_utility_calculate_rankings")
     */
    public function calculateGlobalRanking(ScoreCalculator $scoreCalculator, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->findOneBy(['id' => $eventRepository->findLastEvent(new DateTime('now', new DateTimeZone('UTC')))]);
        $scoreCalculator->calculateGlobalRankings($event);
        $this->addFlash('success', 'calcul du classement g??n??ral effectu??');
        return $this->redirectToRoute('back_utility');
    }
}
