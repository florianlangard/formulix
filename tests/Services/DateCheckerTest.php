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

    public function testDateCheckerWithValidDate()
    {
        self::bootKernel();
        $container = static::getContainer();
        $dateChecker = $container->get(DateChecker::class);
        $validDate = $dateChecker->checkDate(new DateTime('+ 1 day'));
        $this->assertTrue($validDate);
    }

    public function testDateCheckerWithNotValidDate()
    {
        self::bootKernel();
        $container = static::getContainer();
        $dateChecker = $container->get(DateChecker::class);
        $validDate = $dateChecker->checkDate(new DateTime('- 1 day'));
        $this->assertFalse($validDate);
    }

}