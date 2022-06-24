<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Podium;
use App\Repository\PodiumRepository;
use App\Repository\PredictionRepository;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;

class PodiumBuilder
{
    private $podiumRepository;
    private $em;
    private $predictionRepository;
    private $scoreRepository;

    public function __construct(PodiumRepository $podiumRepository, PredictionRepository $predictionRepository, ScoreRepository $scoreRepository, EntityManagerInterface $em)
    {
        $this->podiumRepository = $podiumRepository;
        $this->predictionRepository = $predictionRepository;
        $this->scoreRepository = $scoreRepository;
        $this->em = $em;
    }

    /**
     * Checks if a podium already exists for the given Event.
     * Creates Podium (if needed) and returns it.
     *
     * @param Event $event
     * @return Podium
     */
    public function podiumEntityChecker(Event $event)
    {
        $existingPodium = $this->podiumRepository->findOneBy(['event' => $event]);
        if (!$existingPodium) {
            $podium = new Podium();
            $podium->setEvent($event);
            $this->em->persist($podium);
            $this->em->flush();
            return $podium;
        }
        return $existingPodium;
    }
    /**
     * Create User's podium for given Event for qualifying predictions
     *
     * @param Event $event
     * @return void
     */
    public function createQualifyingPodium(Event $event)
    {
        $podium = $this->podiumEntityChecker($event);
        $predictions = $this->predictionRepository->findPodium($event);

        if (isset($predictions[0])) {
            $podium->setQualifyingFirst($predictions[0]);
            $user = $predictions[0]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getQualifyingWins();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setQualifyingWins($previousScore + 1);
            $this->em->persist($score);
        }
        if (isset($predictions[1])) {
            $podium->setQualifyingSecond($predictions[1]);
            $user = $predictions[1]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getQualifyingSecond();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setQualifyingSecond($previousScore + 1);
            $this->em->persist($score);
        }
        if (isset($predictions[2])) {
            $podium->setQualifyingThird($predictions[2]);
            $user = $predictions[2]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getQualifyingThird();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setQualifyingThird($previousScore + 1);
            $this->em->persist($score);
        }
        // isset($predictions[0]) ? $podium->setQualifyingFirst($predictions[0]) : null;
        // isset($predictions[1]) ? $podium->setQualifyingSecond($predictions[1]) : null;
        // isset($predictions[2]) ? $podium->setQualifyingThird($predictions[2]) : null;
        $this->em->persist($podium);
        $this->em->flush();
    }

    /**
     * Updates User's podium for given Event for race predictions
     *
     * @param Event $event
     * @return void
     */
    public function createRacePodium(Event $event)
    {
        $podium = $this->podiumEntityChecker($event);
        $predictions = $this->predictionRepository->findRacePodium($event);
        if (isset($predictions[0])) {
            $podium->setRaceFirst($predictions[0]);
            $user = $predictions[0]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getRaceWins();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setRaceWins($previousScore + 1);
            $this->em->persist($score);
        }
        if (isset($predictions[1])) {
            $podium->setRaceSecond($predictions[1]);
            $user = $predictions[1]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getRaceSecond();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setRaceSecond($previousScore + 1);
            $this->em->persist($score);
        }
        if (isset($predictions[2])) {
            $podium->setRaceThird($predictions[2]);
            $user = $predictions[2]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getRaceThird();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setRaceThird($previousScore + 1);
            $this->em->persist($score);
        }
        // isset($predictions[0]) ? $podium->setRaceFirst($predictions[0]) : null;
        // isset($predictions[1]) ? $podium->setRaceSecond($predictions[1]) : null;
        // isset($predictions[2]) ? $podium->setRaceThird($predictions[2]) : null;
        $this->em->flush();
    }


    /**
     * Updates User's podium for given Event for race predictions
     *
     * @param Event $event
     * @return void
     */
    public function createGlobalEventPodium(Event $event)
    {
        $podium = $this->podiumEntityChecker($event);
        $predictions = $this->predictionRepository->findGlobalPodium($event);
        if (isset($predictions[0])) {
            $podium->setEventFirst($predictions[0]);
            $user = $predictions[0]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getEventWins();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setEventWins($previousScore + 1);
            $this->em->persist($score);
        }
        if (isset($predictions[1])) {
            $podium->setEventSecond($predictions[1]);
            $user = $predictions[1]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getEventSecond();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setEventSecond($previousScore + 1);
            $this->em->persist($score);
        }
        if (isset($predictions[2])) {
            $podium->setEventThird($predictions[2]);
            $user = $predictions[2]->getUser();
            $score = $this->scoreRepository->findOneBy(['user' => $user]);
            $previousScore = $score->getEventThird();
            if ($previousScore === null) {
                $previousScore = 0;
            }
            $score->setEventThird($previousScore + 1);
            $this->em->persist($score);
        }
        // isset($predictions[0]) ? $podium->setEventFirst($predictions[0]) : null;
        // isset($predictions[1]) ? $podium->setEventSecond($predictions[1]) : null;
        // isset($predictions[2]) ? $podium->setEventThird($predictions[2]) : null;
        $this->em->flush();
    }
}