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
use App\Repository\ScoreRepository;
use App\Repository\PredictionRepository;
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
        ScoreCalculator $scoreCalculator): Response
    {
            $date = new DateTime();

            $nextEvent = $eventRepository->findNextEvent($date);
            $lastEvent = $eventRepository->findLastEvent($date);
            $topTen = $scoreRepository->findTopTen();

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
                $lastEventPodium = $predictionRepository->findPodium($lastEvent[0]->getId());

                if ($lastEventPodium === null) {
                    return $lastEventPodium;
                }
            }
            else {
                $lastEventPodium = null;
            }
            
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
            ]);
    }
}
