<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create:admin';
    protected static $defaultDescription = 'Create a admin user account';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O'); // test
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPersonaname('admin');

        $this->em->persist($user);
        $this->em->flush();

        $io->success('Admin created!');

        return Command::SUCCESS;
    }
}