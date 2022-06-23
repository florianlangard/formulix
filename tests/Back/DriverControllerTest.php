<?php

namespace App\Tests\Back;

use App\Repository\UserRepository;
use App\Repository\DriverRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DriverControllerTest extends WebTestCase
{
    public function testDisplayDriversList(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/back/driver/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Driver index');
    }

    public function testShowDriver(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $driverRepository = static::getContainer()->get(DriverRepository::class);
        $driver = $driverRepository->findOneBy(['number' => 1]);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/back/driver/'.$driver->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Driver');
    }
}
