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

    public function testNewDriver(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/back/driver/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Create new Driver');

        $crawler = $client->submitForm('Save', [
            'driver[fullname]' => 'Didier Test',
            'driver[number]' => 21,
            'driver[driver_id]' => 'test',
            'driver[code]' => 'TST',
            'driver[isActive]' => true,
        ]);
        $this->assertResponseRedirects('/back/driver/');
    }

    public function testEditDriver(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $driverRepository = static::getContainer()->get(DriverRepository::class);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        $testDriver = $driverRepository->findOneBy(['driver_id' => 'test']);
        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/back/driver/'.$testDriver->getId().'/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Edit Driver');

        $crawler = $client->submitForm('Update', [
            'driver[fullname]' => 'modified Test',
            'driver[number]' => 21,
            'driver[driver_id]' => 'test',
            'driver[code]' => 'TST',
            'driver[isActive]' => true,
        ]);
        $this->assertResponseRedirects('/back/driver/');
    }

    // public function testDeleteDriver(): void
    // {
    //     $client = static::createClient();
    //     $userRepository = static::getContainer()->get(UserRepository::class);
    //     $driverRepository = static::getContainer()->get(DriverRepository::class);
        
    //     // retrieve the test user
    //     $testUser = $userRepository->findOneByEmail('admin@test.com');
    //     $testDriver = $driverRepository->findOneBy(['driver_id' => 'test']);
    //     // simulate $testUser being logged in
    //     $client->loginUser($testUser);
    //     $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('delete')->getValue();
    //     $client->request('POST', '/back/driver/'.$testDriver->getId(), ['driver' => ['_token' => $csrfToken]]);
    //     $this->assertResponseRedirects('/back/driver/');
    // }
}
