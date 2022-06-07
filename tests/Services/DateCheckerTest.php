<?php

namespace App\Tests\Services;

use App\Service\DateChecker;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\EventRepository;

class DateCheckerTest extends KernelTestCase {

    public function testDateCheckerWithValidRaceDate()
    {
        self::bootKernel();
        $container = static::getContainer();
        $dateChecker = $container->get(DateChecker::class);
        $eventRepository = $container->get(EventRepository::class);
        $testEvent = $eventRepository->findOneBy(['round' => 19, 'season' => '2022']);
        $validDate = $dateChecker->checkDate($testEvent->getDate());
        $this->assertTrue($validDate);
    }

    public function testDateCheckerWithValidQualifyingDate()
    {
        self::bootKernel();
        $container = static::getContainer();
        $dateChecker = $container->get(DateChecker::class);
        $eventRepository = $container->get(EventRepository::class);
        $testEvent = $eventRepository->findOneBy(['round' => 19, 'season' => '2022']);
        $validDate = $dateChecker->checkDate($testEvent->getQualifyingDate());
        $this->assertTrue($validDate);
    }

    public function testDateCheckerWithNotValidRaceDate()
    {
        self::bootKernel();
        $container = static::getContainer();
        $dateChecker = $container->get(DateChecker::class);
        $eventRepository = $container->get(EventRepository::class);
        $testEvent = $eventRepository->findOneBy(['round' => 0, 'season' => '2022']);
        $validDate = $dateChecker->checkDate($testEvent->getDate());
        $this->assertFalse($validDate);
    }

    public function testDateCheckerWithNotValidQualifyingDate()
    {
        self::bootKernel();
        $container = static::getContainer();
        $dateChecker = $container->get(DateChecker::class);
        $eventRepository = $container->get(EventRepository::class);
        $testEvent = $eventRepository->findOneBy(['round' => 0, 'season' => '2022']);
        $validDate = $dateChecker->checkDate($testEvent->getQualifyingDate());
        $this->assertFalse($validDate);
    }
}