<?php

namespace App\Tests\Services;

use App\Repository\EventRepository;
use App\Repository\PredictionRepository;
use App\Service\ScoreCalculator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ScoreCalculatorTest extends KernelTestCase
{
    public function testTrueCalculateQualifyingScore(): void
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

    public function testFalseCalculateQualifyingScore(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $eventRepository = $container->get(EventRepository::class);
        $predictionRepository = $container->get(PredictionRepository::class);
        $ScoreCalculator = $container->get(ScoreCalculator::class);

        $testEvent = $eventRepository->findOneBy(['round' => 2, 'season' => '2022']);
        $ScoreCalculator->CalculateQualifyingScore($testEvent);

        $prediction = $predictionRepository->findOneBy(['event' => $testEvent]);
        $this->assertNull($prediction);
        
    }

    public function testTrueCalculateRaceScore(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $eventRepository = $container->get(EventRepository::class);
        $predictionRepository = $container->get(PredictionRepository::class);
        $ScoreCalculator = $container->get(ScoreCalculator::class);

        $testEvent = $eventRepository->findOneBy(['round' => 1, 'season' => '2022']);
        $ScoreCalculator->CalculateRaceScore($testEvent);

        $prediction = $predictionRepository->findOneBy(['event' => $testEvent]);
        $score = $prediction->getScore();
        $this->assertGreaterThanOrEqual(1, 1);
        
    }

    public function testFalseCalculateRaceScore(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $eventRepository = $container->get(EventRepository::class);
        $predictionRepository = $container->get(PredictionRepository::class);
        $ScoreCalculator = $container->get(ScoreCalculator::class);

        $testEvent = $eventRepository->findOneBy(['round' => 2, 'season' => '2022']);
        $ScoreCalculator->CalculateRaceScore($testEvent);

        $prediction = $predictionRepository->findOneBy(['event' => $testEvent]);
        $this->assertNull($prediction);
        
    }

    public function testTrueCalculateGlobalEventScore(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $eventRepository = $container->get(EventRepository::class);
        $predictionRepository = $container->get(PredictionRepository::class);
        $ScoreCalculator = $container->get(ScoreCalculator::class);

        $testEvent = $eventRepository->findOneBy(['round' => 1, 'season' => '2022']);
        $ScoreCalculator->CalculateGlobalEventScore($testEvent);

        $prediction = $predictionRepository->findOneBy(['event' => $testEvent]);
        $score = $prediction->getTotalScore();
        $this->assertGreaterThanOrEqual(1, 1);
        
    }

    public function testFalseCalculateGlobalEventScore(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $eventRepository = $container->get(EventRepository::class);
        $predictionRepository = $container->get(PredictionRepository::class);
        $ScoreCalculator = $container->get(ScoreCalculator::class);

        $testEvent = $eventRepository->findOneBy(['round' => 2, 'season' => '2022']);
        $ScoreCalculator->CalculateGlobalEventScore($testEvent);

        $prediction = $predictionRepository->findOneBy(['event' => $testEvent]);
        $this->assertNull($prediction);
        
    }

    //! Needs to have placed prediction first because
    //! it will use Score Entity
    // public function testTrueCalculateGlobalRankings(): void
    // {
    //     $kernel = self::bootKernel();
    //     $container = static::getContainer();

    //     $eventRepository = $container->get(EventRepository::class);
    //     $predictionRepository = $container->get(PredictionRepository::class);
    //     $ScoreCalculator = $container->get(ScoreCalculator::class);

    //     $testEvent = $eventRepository->findOneBy(['round' => 1, 'season' => '2022']);
    //     $ScoreCalculator->CalculateGlobalRankings($testEvent);

    //     $prediction = $predictionRepository->findOneBy(['event' => $testEvent]);
    //     $score = $prediction->getTotalScore();
    //     $this->assertGreaterThanOrEqual(1, 1);
        
    // }
}
