<?php

namespace App\Command;

use App\Entity\Result;
use App\Repository\DriverRepository;
use App\Repository\EventRepository;
use App\Repository\ResultRepository;
use App\Service\FOneApi;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QualifyingResultsCommand extends Command
{
    protected static $defaultName = 'app:quali:result';
    protected static $defaultDescription = 'Get qualification result';

    private $resultRepository;
    private $eventRepository;
    private $driverRepository;
    private $fOneApi;
    private $em;

    public function __construct(ResultRepository $resultRepository, EventRepository $eventRepository, DriverRepository $driverRepository, FOneApi $fOneApi, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->resultRepository = $resultRepository;
        $this->eventRepository = $eventRepository;
        $this->driverRepository = $driverRepository;
        $this->fOneApi = $fOneApi;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED, 'enter the season you look for')
            ->addArgument('round', InputArgument::REQUIRED, 'enter the round number of requested race');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $year = $input->getArgument('year');
        $round = $input->getArgument('round');

        $qualifyingResult = $this->fOneApi->fetchQualifyingResults($year, $round);
        $filteredData = $qualifyingResult['MRData']['RaceTable']['Races'][0]['QualifyingResults'][0];

        $event = $this->eventRepository->findOneBy(['season' => $year, 'round' => $round]);
        $driver = $this->driverRepository->findOneBy(['driver_id' => $filteredData['Driver']['driverId']]);

        $lastResult = $this->resultRepository->findOneBy(['event' => $event->getId()-1]);

        // if ($lastResult->getId() === $event->getId()) 
        // {
        //     $io->warning('Result already fetched!');
        //     return Command::FAILURE;
        // }
        // else 
        // {
            $result = new Result();
    
            $result->setPole($driver);
            $result->setTime($filteredData['Q3']);
            $result->setEvent($event);
            $result->setUpdatedAt(new DateTime());
            
            $this->em->persist($result);
            $this->em->flush();
    
            $io->success('Results fetched!');
    
            return Command::SUCCESS;

        // }

    }
}