<?php

namespace App\Controller\Api;

use App\Entity\Score;
use App\Repository\ScoreRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends AbstractController
{
    /**
     * Get Qualifying Global Rankings
     * @Route("/api/rankings/qualifying", name="api_qualifying_rankings_get", methods="GET")
     */
    public function getQualifyingGlobalRanking(ScoreRepository $scoreRepository): Response
    {
        $scores = $scoreRepository->findBy([], ['qualifyingScore' => 'DESC']);
        return $this->json($scores, Response::HTTP_OK, [], ['groups' => ['rankings', 'rankings_qualifying']]);
    }

    /**
     * Get Races Global Rankings
     * @Route("/api/rankings/race", name="api_race_rankings_get", methods="GET")
     */
    public function getRaceGlobalRanking(ScoreRepository $scoreRepository): Response
    {
        $scores = $scoreRepository->findBy([], ['raceScore' => 'DESC']);
        return $this->json($scores, Response::HTTP_OK, [], ['groups' => ['rankings', 'rankings_race']]);
    }

    /**
     * Get Global Rankings
     * @Route("/api/rankings/global", name="api_global_rankings_get", methods="GET")
     */
    public function getGlobalRanking(ScoreRepository $scoreRepository): Response
    {
        $scores = $scoreRepository->findBy([], ['total' => 'DESC']);
        return $this->json($scores, Response::HTTP_OK, [], ['groups' => ['rankings', 'rankings_global']]);
    }
}