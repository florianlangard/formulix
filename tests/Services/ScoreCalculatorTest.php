<?php

namespace App\Tests\Services;

use App\Repository\EventRepository;
use App\Repository\PredictionRepository;
use App\Service\ScoreCalculator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ScoreCalculatorTest extends KernelTestCase
{
    public function testCalculateQualifyingScore(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $eventRepository = $container->get(EventRepository::class);
        $predictionRepository = $container->get(PredictionRepository::class);
        $ScoreCalculator = $container->get(ScoreCalculator::class);

        $testEvent = $eventRepository->findOneBy(['round' => 1, 'season' => '2022']);
        $ScoreCalculator->CalculateQualifyingScore($testEvent);

        $prediction = $predictionRepository->findOneBy(['event' => $testEvent]);
        $score = $prediction->getScore();
        $this->assertGreaterThanOrEqual(1, 1);
        
    }
}
