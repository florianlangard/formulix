<?php

namespace App\Service;

use App\Repository\EventRepository;
use App\Repository\PredictionRepository;
use App\Repository\ResultRepository;
use App\Repository\ScoreRepository;
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
    private $eventRepository;
    private $resultRepository;
    private $converter;

    public function __construct(
        HttpClientInterface $client,
        ManagerRegistry $doctrine,
        PredictionRepository $predictionRepository,
        ScoreRepository $scoreRepository,
        EventRepository $eventRepository,
        ResultRepository $resultRepository,
        FormatConverter $converter
        )
    {
        $this->client = $client;
        $this->doctrine = $doctrine;
        $this->predictionRepository = $predictionRepository;
        $this->scoreRepository = $scoreRepository;
        $this->eventRepository = $eventRepository;
        $this->resultRepository = $resultRepository;
        $this->converter = $converter;
    }

    /**
     * Calculate the score of every prediction placed on an event
     * Must be called when the result of the given event has been imported
     *
     * @return void
     */
    public function calculateScore($event)
    {
        // Get last Event and his Result
        $lastResult =  $this->resultRepository->findOneBy(['event' => $event]);
        $predictions = $this->predictionRepository->findBy(['event' => $event]);
        // dd($predictions);
        
        
        // Logic to convert result time in the required format to compare
        $data = $lastResult->getTime();
        $time = explode(':', $data);
        $minute = intval($time[0]);
        $second = explode('.', $time[1]);
        $sec = intval($second[0]);
        $msec = intval($second[1]);

        $resultTime = $this->converter->convertToMs($minute, $sec, $msec);
        
        
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
                $time = explode(':', $data);
                $minute = intval($time[0]);
                $second = explode('.', $time[1]);
                $sec = intval($second[0]);
                $msec = intval($second[1]);

                $predictedTime = $this->converter->convertToMs($minute, $sec, $msec);
                $diff = abs($resultTime - $predictedTime);
                // dump($diff);
                

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
                $em->persist($prediction);
                $em->flush();
            }
            // return;
        }
    }


    /**
     * Calculate global score of all users who placed a prediction on a given event.
     * Must be called when every individual prediction score has been calculated.
     *
     * @return void
     */
    public function calculateGlobalScore()
    {
        $date = new DateTime();
        $lastEvent = $this->eventRepository->findLastEvent($date);
        $predictions = $this->predictionRepository->findBy(['event' => $lastEvent]);
        // dd($predictions);

        foreach ($predictions as $prediction) {
            $user = $prediction->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user, 'season' => '2022']);
            // dd($score, $score->getLastEvent(), $lastEvent);
            if ($score->getLastEvent() !== null) {
                if ($score->getLastEvent()->getId() !== $lastEvent[0]->getId()) {
                    $score->setLastEvent($lastEvent[0]);
                    $current = $score->getTotal();
                    $score->setTotal($current += $prediction->getScore());
                }
            }
            else {
                $score->setLastEvent($lastEvent[0]);
                $current = $score->getTotal();
                $score->setTotal($current += $prediction->getScore());

            }
            

            // dd($score);
            $em = $this->doctrine->getManager();
            $em->persist($score);
            dump($score);
            $em->flush();
        }
    }

}