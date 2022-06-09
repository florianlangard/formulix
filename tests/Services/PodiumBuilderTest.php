<?php

namespace App\Tests\Services;

use App\Entity\Podium;
use App\Service\PodiumBuilder;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\EventRepository;

class PodiumBuilderTest extends KernelTestCase 
{
    public function testCreationOfPodium()
    {
        self::bootKernel();
        $container = static::getContainer();
        $podiumBuilder = $container->get(PodiumBuilder::class);
        $eventRepository = $container->get(EventRepository::class);
        $testEvent = $eventRepository->findOneBy(['round' => 1, 'season' => '2022']);
        $podium = $podiumBuilder->podiumEntityChecker($testEvent);
        $this->assertInstanceOf(Podium::class, new Podium);
    }
}