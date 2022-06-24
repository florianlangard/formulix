<?php

namespace App\Command;

use App\Service\DataImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeasonDataCommand extends Command
{
    protected static $defaultName = 'app:season:data';
    protected static $defaultDescription = 'Fetch given season data from API';

    private $dataImporter;

    public function __construct(DataImporter $dataImporter)
    {
        parent::__construct();
        $this->dataImporter = $dataImporter;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED, 'which season ?');
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
        $year = $input->getArgument('year');

        $this->dataImporter->ImportSeasonDrivers($year);  
        $this->dataImporter->ImportSeasonSchedule($year);  
        
        $io->success('Data for season '.$year.' fetched!');

        return Command::SUCCESS;
    }
}