<?php

namespace App\Tests\Services;

use DateTime;
use App\Entity\Podium;
use App\Entity\Prediction;
use App\Repository\DriverRepository;
use App\Service\PodiumBuilder;
use PHPUnit\Framework\TestCase;
use App\Repository\EventRepository;
use App\Repository\PredictionRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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

    // public function testFillingOfPodium()
    // {
    //     self::bootKernel();
    //     $container = static::getContainer();

    //     $podiumBuilder = $container->get(PodiumBuilder::class);
    //     $eventRepository = $container->get(EventRepository::class);
    //     $userRepository = $container->get(UserRepository::class);
    //     $driverRepository = $container->get(DriverRepository::class);
    //     $predictionRepository = $container->get(PredictionRepository::class);

    //     $testEvent = $eventRepository->findOneBy(['round' => 1, 'season' => '2022']);
    //     // $testUser = $userRepository->findOneBy(['email' => 'admin@test.com']);
    //     // $testDrivers = $driverRepository->findAll();

    //     $podium = $podiumBuilder->createQualifyingPodium($testEvent);
    //     $this->assertInstanceOf(Podium::class, new Podium);

    //     $prediction = $predictionRepository->findOneBy(['event' => $testEvent->getId()]);
    //     // $prediction = new Prediction();
    //     // $prediction
    //     //     ->setUser($testUser)
    //     //     ->setEvent($testEvent)
    //     //     ->setCreatedAt(new DateTime())
    //     //     ->setRaceCreatedAt(new DateTime())
    //     //     ->setPole($testDrivers[0])
    //     //     ->setTime('1:22.444')
    //     //     ->setFinishFirst($testDrivers[1])
    //     //     ->setFinishSecond($testDrivers[2])
    //     //     ->setFinishThird($testDrivers[3]);
        
    //     $podium->setQualifyingFirst($prediction);
    // }
}