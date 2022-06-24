<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\PredictionRepository;
use App\Repository\ScoreRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    /**
     * @Route("/stats", name="stats")
     */
    public function index(ScoreRepository $scoreRepository, PredictionRepository $predictionRepository, EventRepository $eventRepository): Response
    {

        $globalRankings = $scoreRepository->findBy(['season' => '2022'], ['total' => 'DESC'], 100);
        $lastEvent = $eventRepository->findLastEvent(new DateTime());
        $lastEventRankings = $predictionRepository->findBy(['event' => $lastEvent], ['score' => 'DESC', 'created_at' => 'ASC', 'updated_at' => 'ASC'], 100);
        $predictionCount = $predictionRepository->getPredictionCount($lastEvent[0]->getId());
        $usersCount = $scoreRepository->getUsersCount('2022');

        return $this->render('stats/index.html.twig', [
            'globalRankings' => $globalRankings,
            'lastEventRankings' => $lastEventRankings,
            'predictionCount' => $predictionCount,
            'usersCount' => $usersCount
        ]);
    }

    /**
     * @Route("/rules", name="rules")
     */
    public function rules(): Response
    {
        return $this->render('stats/rules.html.twig');
    }
}
