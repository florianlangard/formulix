<?php

namespace App\Controller\Back;

use App\Repository\EventRepository;
use App\Repository\PredictionRepository;
use App\Repository\ResultRepository;
use App\Repository\ScoreRepository;
use App\Service\DataImporter;
use App\Service\FOneApi;
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

        $lastEvent = $eventRepository->findLastEvent(new DateTime('now', new DateTimeZone('UTC')));
        $lastResult = $resultRepository->findOneBy([], ['id' => 'DESC']);
        // dd($lastResult);
        
        $checkPredictions = $predictionRepository->findOneBy(['event' => $lastResult->getEvent()]);
        
        $checkRankings = $scoreRepository->findOneBy(['lastEvent' => $lastResult->getEvent()]);
        // dd($checkRankings);
        if ($checkPredictions === null) {
            $toCalculate = false;
        }
        if ($checkPredictions !== null && $checkPredictions->getScore() !== null) {
            $toCalculate = false;
        }
        
        if ($lastEvent[0]->getName() === $lastResult->getEvent()->getName()) {
            $toUpdate = false;
        }
        if ($checkRankings !== null) {
            if ($checkRankings->getLastEvent() === $lastEvent[0]) {
                $toCalculateRanking = false;
            }
        }
        return $this->render('back/utility/index.html.twig', [
            'lastEvent' => $lastEvent,
            'lastResult' => $lastResult,
            'toUpdate' => $toUpdate,
            'toCalculate' => $toCalculate,
            'toCalculateRanking' => $toCalculateRanking,
        ]);
    }

    /**
     * @Route("/back/utility/get_result", name="back_utility_get_result")
     */
    public function getLastResult(EventRepository $eventRepository, ResultRepository $resultRepository, FOneApi $fOneApi, DataImporter $dataImporter): Response
    {
        $done = 'fetched';
        $lastEvent = $eventRepository->findLastEvent(new DateTime('now', new DateTimeZone('UTC')));
        // $qualifyingData = $fOneApi->fetchQualifyingResults($lastEvent[0]->getSeason(), $lastEvent[0]->getRound());
        $result = $resultRepository->findOneBy(['event' => $lastEvent[0]]);
        dd($result);
        // $test = $dataImporter->ImportSeasonDrivers('2022');
        // dd($test);
        return $this->redirectToRoute('back_utility', ['status' => $done]);
    }

    /**
     * @Route("/back/utility/calculate_scores", name="back_utility_calculate_scores")
     */
    public function calculateScores(ScoreCalculator $scoreCalculator, EventRepository $eventRepository): Response
    {
        
        $done = 'calculated';
        $event = $eventRepository->findOneBy(['round' => 7]); //! Hardcode

        $calculateQualifyingScore = $scoreCalculator->calculateQualifyingScore($event);
        if ($calculateQualifyingScore === false) {
            $this->addFlash('error', 'Impossible de calculer ces scores (Qualifs), résultats indisponibles');
            return $this->redirectToRoute('back_utility');
        } else {
            $this->addFlash('success', 'calcul des résultats (Qualifs) effectué');
        }

        $calculateRaceScore = $scoreCalculator->calculateRaceScore($event);
        if ($calculateRaceScore === false) {
            $this->addFlash('error', 'Impossible de calculer les scores (Course), résultats indisponibles');
            return $this->redirectToRoute('back_utility');
        } else {
            $this->addFlash('success', 'calcul des résultats (Course) effectué');
        }

        $scoreCalculator->calculateGlobalEventScore($event);
        $this->addFlash('success', 'calcul des scores globaux effectué');

        return $this->redirectToRoute('back_utility', ['status' => $done]);
    }

    /**
     * @Route("/back/utility/calculate_rankings", name="back_utility_calculate_rankings")
     */
    public function calculateGlobalRanking(ScoreCalculator $scoreCalculator, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->findOneBy(['round' => 7]); //! Hardcode
        $scoreCalculator->calculateGlobalRankings($event);
        $this->addFlash('success', 'calcul du classement général effectué');
        return $this->redirectToRoute('back_utility');
    }
}
