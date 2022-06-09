<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Podium;
use App\Repository\PodiumRepository;
use App\Repository\PredictionRepository;
use Doctrine\ORM\EntityManagerInterface;

class PodiumBuilder
{
    private $podiumRepository;
    private $em;
    private $predictionRepository;

    public function __construct(PodiumRepository $podiumRepository, PredictionRepository $predictionRepository, EntityManagerInterface $em)
    {
        $this->podiumRepository = $podiumRepository;
        $this->predictionRepository = $predictionRepository;
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
        isset($predictions[0]) ? $podium->setQualifyingFirst($predictions[0]) : null;
        isset($predictions[1]) ? $podium->setQualifyingSecond($predictions[1]) : null;
        isset($predictions[2]) ? $podium->setQualifyingThird($predictions[2]) : null;
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
        isset($predictions[0]) ? $podium->setRaceFirst($predictions[0]) : null;
        isset($predictions[1]) ? $podium->setRaceSecond($predictions[1]) : null;
        isset($predictions[2]) ? $podium->setRaceThird($predictions[2]) : null;
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
        isset($predictions[0]) ? $podium->setEventFirst($predictions[0]) : null;
        isset($predictions[1]) ? $podium->setEventSecond($predictions[1]) : null;
        isset($predictions[2]) ? $podium->setEventThird($predictions[2]) : null;
        $this->em->flush();
    }
}