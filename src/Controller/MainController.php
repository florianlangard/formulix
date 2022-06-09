<?php

namespace App\Controller;

use DateTime;
use App\Entity\Driver;
use App\Entity\Prediction;
use App\Entity\Result;
use App\Repository\DriverRepository;
use App\Service\FOneApi;
use App\Service\ScoreCalculator;
use App\Repository\EventRepository;
use App\Repository\PodiumRepository;
use App\Repository\ScoreRepository;
use App\Repository\PredictionRepository;
use App\Service\PodiumBuilder;
use DateTimeZone;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="landing_page")
     */
    public function landingPage() {
        return $this->render('main/landing.html.twig');
    }

    /**
     * @Route("/home", name="home")
     */
    public function home(
        FOneApi $api, 
        EventRepository $eventRepository, 
        ScoreRepository $scoreRepository, 
        PredictionRepository $predictionRepository,
        DriverRepository $driverRepository,
        SluggerInterface $slugger,
        PodiumRepository $podiumRepository,
        PodiumBuilder $podiumBuilder,
        ScoreCalculator $scoreCalculator): Response
    {
            $date = new DateTime('now', new DateTimeZone('UTC'));

            $nextEvent = $eventRepository->findNextEvent($date);
            $lastEvent = $eventRepository->findLastEvent($date);
            $topTen = $scoreRepository->findTopTen();
            $eventPodium = $podiumRepository->findOneBy(['event' => $lastEvent]);
            // $podiumBuilder->createQualifyingPodium($nextEvent[0]);
            
            $total = $scoreRepository->findBy(['user' => $this->getUser(), 'season' => 2022]);
            $count = $predictionRepository->getUserPredictionCount($this->getUser());
            $predictions = $predictionRepository->findAll();
            // dd($predictions);
            if ($predictions != null) {
                $totalCount = $predictionRepository->getPredictionCount($nextEvent[0]->getId());
                $totalCountNext = $predictionRepository->getPredictionCount($nextEvent[1]->getId());
            }
            else {
                $totalCount = null;
                $totalCountNext = null;
            }

            if ($lastEvent) {
                $lastEventPodium = $predictionRepository->findPodium($lastEvent[0]);
                $lastEventRacePodium = $predictionRepository->findRacePodium($lastEvent[0]);
                $lastEventGlobalPodium = $predictionRepository->findGlobalPodium($lastEvent[0]);

                if ($lastEventPodium === null) {
                    return $lastEventPodium;
                }
            }
            else {
                $lastEventPodium = null;
            }
            // dd($lastEventRacePodium, $lastEventPodium);
            return $this->render('main/index.html.twig',[
                'nextEvent' => $nextEvent, 
                'lastEvent' => $lastEvent, 
                'topTen' => $topTen, 
                'podium' => $lastEventPodium,
                'predictionCount' => $count,
                'total' => $total,
                'date' => $date,
                'totalCount' => $totalCount,
                'totalCountNext' => $totalCountNext,
                'racePodium' => $lastEventRacePodium,
                'globalPodium' => $lastEventGlobalPodium
            ]);
    }
}
