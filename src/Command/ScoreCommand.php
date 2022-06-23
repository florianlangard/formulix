<?php

namespace App\Command;

use App\Entity\Result;
use App\Repository\EventRepository;
use App\Repository\ResultRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScoreCommand extends Command
{
    protected static $defaultName = 'app:score:calc';
    protected static $defaultDescription = 'Calculate score';

    private $resultRepository;
    private $eventRepository;

    public function __construct(ResultRepository $resultRepository, EventRepository $eventRepository, ManagerRegistry $doctrine)
    {
        parent::__construct();
        $this->resultRepository = $resultRepository;
        $this->eventRepository = $eventRepository;
        $this->doctrine = $doctrine;
    }

    protected function configure(): void
    {
        
    }

    /**
     * Undocumented function
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $io = new SymfonyStyle($input, $output);
        // $year = $input->getArgument('year');
        // $round = $input->getArgument('round');

        // $event = $this->eventRepository->findOneBy(['round' => $round]);

        // $raceResult = $this->fOneApi->fetchQualiResult($year, $round)->toArray();

        // $result = new Result();

        // $result->setEvent($event);
        // $result->setType('quali');
        // $result->setFullResult($raceResult);

        // $this->em->persist($result);
        // $this->em->flush();  
        
        // $io->success('Results fetched!');

        return Command::SUCCESS;
    }
}