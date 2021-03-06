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
use App\Repository\UserRepository;
use DateTimeZone;
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
    private $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, EventRepository $eventRepository, DriverRepository $driverRepository, ScoreRepository $scoreRepository)
    {
        parent::__construct();
        $this->em = $em;
        $this->eventRepository = $eventRepository;
        $this->driverRepository = $driverRepository;
        $this->scoreRepository = $scoreRepository;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this->addArgument('round', InputArgument::REQUIRED, 'enter the round number of requested race');
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
        $io = new SymfonyStyle($input, $output);
        $round = $input->getArgument('round');

        // for ($i = 1; $i <= 19; $i++) {
        //     $user = new User();
        //     $user->setEmail('user'.$i.'@test.com');
        //     $user->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O');
        //     $user->setRoles(['ROLE_USER']);
        //     $user->setPersonaname('user'.$i);

        //     $users[] = $user;

        //     $this->em->persist($user);
            
        // }

        $users = $this->userRepository->findAll();
        $drivers = $this->driverRepository->findAll();

        foreach ($users as $u) {
            $prediction = new Prediction();
            $prediction->setEvent($this->eventRepository->findOneBy(['round' => $round, 'season' => '2022'])); // Id of last past event
            $prediction->setCreatedAt(new DateTime('now', new DateTimeZone('UTC')));
            $prediction->setUser($u);
            $pole = $this->driverRepository->findOneBy(['id' => rand($drivers[0]->getId(),$drivers[count($drivers)-1]->getId())]);
            $prediction->setPole($pole);
            $prediction->setTime('1:'.rand(16, 26).'.'.random_int(0,9).random_int(0,9).random_int(0,9));

            $first = $this->driverRepository->findOneBy(['id' => rand($drivers[0]->getId(),$drivers[count($drivers)-1]->getId())]);
            $prediction->setFinishFirst($first);
            $second = $this->driverRepository->findOneBy(['id' => rand($drivers[0]->getId(),$drivers[count($drivers)-1]->getId())]);
            while ($second === $first) {
                $second = $this->driverRepository->findOneBy(['id' => rand($drivers[0]->getId(),$drivers[count($drivers)-1]->getId())]);
            }
            $third = $this->driverRepository->findOneBy(['id' => rand($drivers[0]->getId(),$drivers[count($drivers)-1]->getId())]);
            while ($third === $first || $third === $second) {
                $third = $this->driverRepository->findOneBy(['id' => rand($drivers[0]->getId(),$drivers[count($drivers)-1]->getId())]);
            }
            $prediction->setFinishSecond($second);
            $prediction->setFinishThird($third);
            $prediction->setRaceCreatedAt(new DateTime('now', new DateTimeZone('UTC')));

            $this->em->persist($prediction);

            if ($this->scoreRepository->findOneBy(['user' => $u]) === null) {
                $score = new Score();
                $score->setUser($u);
                $score->setSeason('2022');
                $this->em->persist($score);
            }

            $this->em->flush();

        }

        $io->success('Populated!');

        return Command::SUCCESS;
    }
}