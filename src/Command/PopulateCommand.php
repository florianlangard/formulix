<?php

namespace App\Command;

use DateTime;
use App\Entity\User;
use App\Entity\Result;
use App\Entity\Prediction;
use App\Entity\Score;
use App\Repository\DriverRepository;
use App\Repository\EventRepository;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateCommand extends Command
{
    protected static $defaultName = 'app:populate';
    protected static $defaultDescription = 'Create users and predictions related';

    private $em;
    private $eventRepository;
    private $driverRepository;
    private $scoreRepository;

    public function __construct(EntityManagerInterface $em, EventRepository $eventRepository, DriverRepository $driverRepository, ScoreRepository $scoreRepository)
    {
        parent::__construct();
        $this->em = $em;
        $this->eventRepository = $eventRepository;
        $this->driverRepository = $driverRepository;
        $this->scoreRepository = $scoreRepository;
    }

    protected function configure(): void
    {
        $this->addArgument('round', InputArgument::REQUIRED, 'enter the round number of requested race');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $round = $input->getArgument('round');

        for ($i = 1; $i <= 19; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@test.com');
            $user->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O');
            $user->setRoles(['ROLE_USER']);
            $user->setPersonaname('user'.$i);

            $users[] = $user;

            $this->em->persist($user);
            
        }

        foreach ($users as $u) {
            $prediction = new Prediction();
            $prediction->setEvent($this->eventRepository->findOneBy(['round' => $round, 'season' => '2022'])); // Id of last past event
            $prediction->setCreatedAt(new DateTime());
            $prediction->setUser($u);
            $driver = $this->driverRepository->find(rand(1,20));
            $prediction->setPole($driver);
            $prediction->setTime('1:'.rand(15, 20).'.'.rand(000,999));

            $this->em->persist($prediction);

            $score = new Score();
            $score->setUser($u);
            $score->setSeason('2022');

            $this->em->persist($score);

            $this->em->flush();

        }

        $io->success('Populated!');

        return Command::SUCCESS;
    }
}