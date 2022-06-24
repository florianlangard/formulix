<?php

namespace App\Tests\Back;

use App\Repository\UserRepository;
use App\Repository\EventRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    public function testDisplayEventsList(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $eventRepository = static::getContainer()->get(EventRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/back/event/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Event index');
    }

    public function testShowEvent(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $eventRepository = static::getContainer()->get(EventRepository::class);
        $event = $eventRepository->findOneBy(['round' => 1, 'season' => '2022']);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/back/event/'.$event->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Event');
    }

    public function testNewEvent(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/back/event/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Create new Event');

        $crawler = $client->submitForm('Save', [
            'event[date][date][day]' => 1,
            'event[date][date][month]' => 1,
            'event[date][date][year]' => 2022,
            'event[date][time][hour]' => 14,
            'event[date][time][minute]' => 10,
            'event[name]' => 'GP Test',
            'event[round]' => 21,
            'event[season]' => '2022',
            'event[circuit_name]' => 'track Test',
            'event[locality]' => 'city test',
            'event[country]' => 'country test',
            'event[qualifying_date][date][day]' => 1,
            'event[qualifying_date][date][month]' => 1,
            'event[qualifying_date][date][year]' => 2022,
            'event[qualifying_date][time][hour]' => 14,
            'event[qualifying_date][time][minute]' => 10,
            'event[slug]' => 'GP-Test'
        ]);
        $this->assertResponseRedirects('/back/event/');
    }

    public function testEditEvent(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $eventRepository = static::getContainer()->get(EventRepository::class);
        $testEvent = $eventRepository->findOneBy(['round' => 21]);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/back/event/'.$testEvent->getId().'/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Edit Event');

        $crawler = $client->submitForm('Update', [
            'event[date][date][day]' => 2,
        ]);
        $this->assertResponseRedirects('/back/event/');
    }
}
