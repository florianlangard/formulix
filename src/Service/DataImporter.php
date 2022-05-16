<?php

namespace App\Service;

use DateTime;
use App\Entity\Result;
use App\Repository\EventRepository;
use App\Repository\DriverRepository;
use App\Repository\ResultRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DataImporter
{
    private $client;
    private $fOneApi;
    private $resultRepository;
    private $eventRepository;
    private $driverRepository;
    private $em;

    public function __construct(
        HttpClientInterface $client,
        FOneApi $fOneApi,
        ResultRepository $resultRepository,
        EventRepository $eventRepository,
        DriverRepository $driverRepository,
        EntityManagerInterface $em
        )
    {
        $this->client = $client;
        $this->fOneApi = $fOneApi;
        $this->resultRepository = $resultRepository;
        $this->eventRepository = $eventRepository;
        $this->driverRepository = $driverRepository;
        $this->em = $em;
    }

    /**
     * Fetches Qualifying results to API and persists it in database.
     *
     * @param int|string $year
     * @param int|string $round
     * @return void
     */
    public function ImportQualifyingResults($year, $round)
    {
        $qualifyingResult = $this->fOneApi->fetchQualifyingResults($year, $round);
        $filteredData = $qualifyingResult['MRData']['RaceTable']['Races'][0]['QualifyingResults'][0];

        $event = $this->eventRepository->findOneBy(['season' => $year, 'round' => $round]);
        $result = $this->resultRepository->findOneBy(['event' => $event]);
        $driver = $this->driverRepository->findOneBy(['driver_id' => $filteredData['Driver']['driverId']]);
        
        if ($result === null) {
            $result = new Result();
        }
            $result->setPole($driver);
            $result->setTime($filteredData['Q3']);
            $result->setEvent($event);
            $result->setUpdatedAt(new DateTime('now', new DateTimeZone('UTC')));
            $this->em->persist($result);
            $this->em->flush();
    }

    /**
     * Fetches Race results to API and persists it in database.
     *
     * @param int|string $year
     * @param int|string $round
     * @return void
     */
    public function ImportRaceResults($year, $round)
    {
        
    }
}