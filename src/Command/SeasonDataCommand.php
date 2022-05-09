<?php

namespace App\Command;

use DateTime;
use DateTimeZone;
use App\Entity\Event;
use App\Entity\Driver;
use App\Entity\Result;
use App\Service\FOneApi;
use App\Repository\EventRepository;
use App\Repository\ResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SeasonDataCommand extends Command
{
    protected static $defaultName = 'app:season:data';
    protected static $defaultDescription = 'Fetch given season data from API';

    private $fOneApi;
    private $eventRepository;
    private $em;
    private $slugger;

    public function __construct(FOneApi $fOneApi, EventRepository $eventRepository, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        parent::__construct();
        $this->fOneApi = $fOneApi;
        $this->eventRepository = $eventRepository;
        $this->em = $em;
        $this->slugger = $slugger;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED, 'which season ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $year = $input->getArgument('year');

        $seasonSchedule = $this->fOneApi->fetchSeasonSchedule($year);
        $seasonDrivers = $this->fOneApi->fetchSeasonDrivers($year);

        $scheduleData = $seasonSchedule['MRData']['RaceTable']['Races'];
        $driversData = $seasonDrivers['MRData']['DriverTable']['Drivers'];
        
        foreach ($scheduleData as $data) {
            $event = new Event();
            $event->setSeason($data['season']);
            $event->setRound($data['round']);
            $event->setName($data['raceName']);
            $event->setCircuitName($data['Circuit']['circuitName']);
            $event->setLocality($data['Circuit']['Location']['locality']);
            $event->setCountry($data['Circuit']['Location']['country']);
            $event->setSlug($this->slugger->slug($data['raceName']));

            $countryCodes = file_get_contents('public/resources/ISO3166-1.json');
            $decoded = json_decode($countryCodes, true);
            $event->setCountryCode($decoded[$data['Circuit']['Location']['country']]);

            $date = $data['date'].'T'.$data['time'];
            $qualifyingDate = $data['Qualifying']['date'].'T'.$data['Qualifying']['time'];
            $date = new DateTime($date, new DateTimeZone('UTC'));
            $qualifyingDate = new DateTime($qualifyingDate, new DateTimeZone('UTC'));
            // $date->format('c');
            // $qualifyingDate->format('c');

            $event->setDate($date);
            $event->setQualifyingDate($qualifyingDate);

            $this->em->persist($event);
        }

        foreach ($driversData as $data) {
            $driver = new Driver();
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
        
        $io->success('Data for season '.$year.' fetched!');

        return Command::SUCCESS;
    }
}