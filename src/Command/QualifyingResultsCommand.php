<?php

namespace App\Command;

use App\Service\DataImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QualifyingResultsCommand extends Command
{
    protected static $defaultName = 'app:quali:result';
    protected static $defaultDescription = 'Get qualification result';

    private $dataImporter;

    public function __construct(DataImporter $dataImporter)
    {
        parent::__construct();
        $this->dataImporter = $dataImporter;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED, 'enter the season you look for')
            ->addArgument('round', InputArgument::REQUIRED, 'enter the round number of requested race');
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
        $round = $input->getArgument('round');

        $this->dataImporter->ImportQualifyingResults($year, $round);

        $io->success('Results fetched!');
        return Command::SUCCESS;
    }
}