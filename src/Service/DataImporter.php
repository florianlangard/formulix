<?php

namespace App\Service;

use DateTime;
use DateTimeZone;
use App\Entity\Event;
use App\Entity\Driver;
use App\Entity\Result;
use Doctrine\ORM\EntityManager;
use App\Repository\EventRepository;
use App\Repository\DriverRepository;
use App\Repository\ResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class DataImporter
{
    private $client;
    private $slugger;
    private $fOneApi;
    private $resultRepository;
    private $eventRepository;
    private $driverRepository;
    private $em;

    public function __construct(
        HttpClientInterface $client,
        SluggerInterface $slugger,
        FOneApi $fOneApi,
        ResultRepository $resultRepository,
        EventRepository $eventRepository,
        DriverRepository $driverRepository,
        EntityManagerInterface $em
        )
    {
        $this->client = $client;
        $this->slugger = $slugger;
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
     * @return Result $result
     */
    public function ImportQualifyingResults($year, $round)
    {
        $qualifyingResult = $this->fOneApi->fetchQualifyingResults($year, $round);
        if (!empty($qualifyingResult['MRData']['RaceTable']['Races'])) {
            $filteredData = $qualifyingResult['MRData']['RaceTable']['Races'][0]['QualifyingResults'][0];
        } else {
            return null;
        };

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
        return $result;
    }

    /**
     * Fetches Race results to API and persists it in database.
     *
     * @param int|string $year
     * @param int|string $round
     * @return Result $result
     */
    public function ImportRaceResults($year, $round)
    {
        $raceResult = $this->fOneApi->fetchRaceResults($year, $round);
        if (!empty($raceResult['MRData']['RaceTable']['Races'])) {
            $filteredData = $raceResult['MRData']['RaceTable']['Races'][0]['Results'];
        } else {
            return null;
        }

        $event = $this->eventRepository->findOneBy(['season' => $year, 'round' => $round]);
        $result = $this->resultRepository->findOneBy(['event' => $event]);

        if ($result === null) {
            $result = new Result();
        }
        $result->setFinishedFirst($this->driverRepository->findOneBy(['driver_id' => $filteredData[0]['Driver']]));
        $result->setFinishedSecond($this->driverRepository->findOneBy(['driver_id' => $filteredData[1]['Driver']]));
        $result->setFinishedThird($this->driverRepository->findOneBy(['driver_id' => $filteredData[2]['Driver']]));
        $result->setUpdatedAt(new DateTime('now', new DateTimeZone('UTC')));
        $this->em->persist($result);
        $this->em->flush();
        return $result;
    }

    /**
     * Fetches given Season schedule, or updates existing one.
     *
     * @param int|string $year
     * @return void
     */
    public function ImportSeasonSchedule($year)
    {
        $seasonSchedule = $this->fOneApi->fetchSeasonSchedule($year);
        $scheduleData = $seasonSchedule['MRData']['RaceTable']['Races'];

        foreach ($scheduleData as $data) {
            $event = $this->eventRepository->findOneBy(['round' => $data['round'], 'season' => $data['season']]);
            if ($event === null) {
                $event = new Event();
                $event->setSeason($data['season']);
            }
            $event->setRound($data['round']);
            $event->setName($data['raceName']);
            $event->setCircuitName($data['Circuit']['circuitName']);
            $event->setLocality($data['Circuit']['Location']['locality']);
            $event->setCountry($data['Circuit']['Location']['country']);
            $event->setSlug($this->slugger->slug($data['raceName']));

            $countryCodes = file_get_contents('../public/resources/ISO3166-1.json');
            $decoded = json_decode($countryCodes, true);
            
            $event->setCountryCode($decoded[$data['Circuit']['Location']['country']]);

            $date = $data['date'].'T'.$data['time'];
            $qualifyingDate = $data['Qualifying']['date'].'T'.$data['Qualifying']['time'];
            $date = new DateTime($date, new DateTimeZone('UTC'));
            $qualifyingDate = new DateTime($qualifyingDate, new DateTimeZone('UTC'));

            $event->setDate($date);
            $event->setQualifyingDate($qualifyingDate);
            $this->em->persist($event);
        }
        $this->em->flush();
    }


    /**
     * Fetches given Season drivers list, or updates existing one.
     *
     * @param int|string $year
     * @return void
     */
    public function ImportSeasonDrivers($year)
    {

        $seasonDrivers = $this->fOneApi->fetchSeasonDrivers($year);
        $driversData = $seasonDrivers['MRData']['DriverTable']['Drivers'];
        
        foreach ($driversData as $data) {
            $driver = $this->driverRepository->findOneBy(['driver_id' => $data['driverId']]);
            if ($driver === null) {
                $driver = new Driver();
            }
            $driver->setFullname($data['givenName'].' '.$data['familyName']);
            if (array_key_exists('permanentNumber', $data)) 
            {
                $driver->setNumber($data['permanentNumber']);
            }
            $driver->setIsActive(true);
            $driver->setDriverId($data['driverId']);
            $driver->setCode($data['code']);
            
            $this->em->persist($driver);
        }
        $this->em->flush();
    }
}