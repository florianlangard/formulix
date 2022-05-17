<?php

namespace App\Service;

use App\Repository\EventRepository;
use App\Repository\PredictionRepository;
use App\Repository\ResultRepository;
use App\Repository\ScoreRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScoreCalculator
{
    private $client;
    private $doctrine;
    private $predictionRepository;
    private $scoreRepository;
    private $resultRepository;
    private $converter;

    public function __construct(
        HttpClientInterface $client,
        ManagerRegistry $doctrine,
        PredictionRepository $predictionRepository,
        ScoreRepository $scoreRepository,
        ResultRepository $resultRepository,
        FormatConverter $converter
        )
    {
        $this->client = $client;
        $this->doctrine = $doctrine;
        $this->predictionRepository = $predictionRepository;
        $this->scoreRepository = $scoreRepository;
        $this->resultRepository = $resultRepository;
        $this->converter = $converter;
    }

    /**
     * Calculate the score of every qualifying prediction placed on an event.
     * Must be called when the result of the given event has been imported.
     *
     * @param App\Entity\Event $event
     * @return void
     */
    public function calculateQualifyingScore($event)
    {
        // Get last Event and his Result
        $lastResult =  $this->resultRepository->findOneBy(['event' => $event]);
        $predictions = $this->predictionRepository->findBy(['event' => $event]);
        if ($lastResult->getPole() === null) {
            return false;
        }
        // Logic to convert result time in the required format to compare
        $data = $lastResult->getTime();
        $unformatted = $this->converter->unformatTimeString($data);
        $resultTime = $this->converter->convertToMs(
            $unformatted['minute'], 
            $unformatted['seconds'], 
            $unformatted['milliseconds']
        );
        // Logic to convert every prediction time in required format to compare
        // and calculate each prediction score
        foreach ($predictions as $prediction) 
        {
            if ($prediction->getScore() === null) 
            {
                $predictionScore = 0;
                $pole = $prediction->getPole();
                if ($pole === $lastResult->getPole()) 
                {
                    $predictionScore += 10;
                }
                $data = $prediction->getTime();
                $unformatted = $this->converter->unformatTimeString($data);
                $predictedTime = $this->converter->convertToMs(
                    $unformatted['minute'], 
                    $unformatted['seconds'], 
                    $unformatted['milliseconds']
                );

                $diff = abs($resultTime - $predictedTime);               
                switch($diff) {
                    case($diff === 0):
                        $predictionScore += 20;
                        break;
                    case ($diff <= 10):
                        $predictionScore += 16;
                        break;
                    case ($diff <= 100):
                        $predictionScore += 10;
                        break;
                    case ($diff <= 500):
                        $predictionScore += 6;
                        break;
                    case ($diff <= 1000):
                        $predictionScore += 3;
                        break;
                    default:
                        $predictionScore += 1;
                }
                $prediction->setScore($predictionScore);
                $em = $this->doctrine->getManager();
            }
            $em->flush();
        }
    }

    /**
     * Calculate the score of every race prediction placed on an event.
     * Must be called when the result of the given event has been imported.
     *
     * @param App\Entity\Event $event
     * @return void
     */
    public function calculateRaceScore($event)
    {
        // Get last Event and his Result
        $lastResult =  $this->resultRepository->findOneBy(['event' => $event]);
        $predictions = $this->predictionRepository->findBy(['event' => $event]);
        if ($lastResult->getFinishedFirst() === null) {
            return false;
        }
        $first = $lastResult->getFinishedFirst();
        $second = $lastResult->getFinishedSecond();
        $third = $lastResult->getFinishedThird();

        foreach ($predictions as $prediction) {
            if ($prediction->getRaceScore() === null) {
                $racePredictionScore = 1;
                if ($prediction->getFinishFirst() === $first) {
                    $racePredictionScore += 9;
                }
                if ($prediction->getFinishSecond() === $second) {
                    $racePredictionScore += 6;
                }
                if ($prediction->getFinishThird() === $third) {
                    $racePredictionScore += 4;
                }
                if ($prediction->getFinishFirst() === $second || $prediction->getFinishFirst() === $third) {
                    $racePredictionScore += 3;
                }
                if ($prediction->getFinishSecond() === $first || $prediction->getFinishSecond() === $third) {
                    $racePredictionScore += 3;
                }
                if ($prediction->getFinishThird() === $first || $prediction->getFinishThird() === $second) {
                    $racePredictionScore += 3;
                }
                $prediction->setRaceScore($racePredictionScore);
                $em = $this->doctrine->getManager();
            }
            $em->flush();
        }
    }

    /**
     * Calculate global score for every prediction placed on a given event.
     * Must be called when every individual prediction score has been calculated.
     *
     * @param App\Entity\Event $event
     * @return void
     */
    public function calculateGlobalEventScore($event)
    {
        $predictions = $this->predictionRepository->findBy(['event' => $event]);
        
        foreach ($predictions as $prediction) {
            $qualifyingScore = $prediction->getScore();
            $raceScore = $prediction->getRaceScore();
            if ($qualifyingScore === null) {
                $qualifyingScore = 0;
            }
            if ($raceScore === null) {
                $raceScore = 0;
            }
            $globalEventScore = $qualifyingScore + $raceScore;
            $prediction->setTotalScore($globalEventScore);
            $em = $this->doctrine->getManager();
        }
        $em->flush();
    }

    /**
     * Calculate Global Rankings.
     *
     * @param App\Entity\Event $event
     * @return void
     */
    public function calculateGlobalRankings($event)
    {
        $predictions = $this->predictionRepository->findBy(['event' => $event]);
        foreach ($predictions as $prediction ) {
            $user = $prediction->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);

            $qualifyingScore = $score->getQualifyingScore();
            $raceScore = $score->getRaceScore();
            $totalScore = $score->getTotal();
            
            $score->setLastEvent($prediction->getEvent());
            $score->setQualifyingScore($qualifyingScore + $prediction->getScore());
            $score->setRaceScore($raceScore  + $prediction->getRaceScore());
            $score->setTotal($totalScore + $prediction->getTotalScore());
            $em = $this->doctrine->getManager();
        }
        $em->flush();
    }

}