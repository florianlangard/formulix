<?php

namespace App\Tests\Front;

use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\DriverRepository;
use App\Repository\PredictionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PredictionControllerTest extends WebTestCase {

    public function testRedirectUnauthenticatedUser()
    {
        $client = static::createClient();
        $client->request('GET', '/prediction/add/qualifying/course-n-18');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    public function testRedirectOnExpiredEvent()
    {
        $client = static::createClient();
        $client->request('GET', '/prediction/add/qualifying/course-n-1');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Tableau de Bord');
    }

    public function testRedirectOnExpiredRaceEvent()
    {
        $client = static::createClient();
        $client->request('GET', '/prediction/add/race/course-n-1');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Tableau de Bord');
    }

    public function testAuthenticatedUserAccessPrediction()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/prediction/add/qualifying/course-n-18');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Pronostics');
    }

    public function testRedirectOnNullRaceEvent()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $client->request('GET', '/prediction/add/race/course-n-99');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Calendrier 2022');
    }

    public function testRedirectOnNullQualifyingEvent()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $client->request('GET', '/prediction/add/qualifying/course-n-99');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Calendrier 2022');
    }

    public function testRedirectOnEditPrediction()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $client->request('GET', '/prediction/add/qualifying/course-n-1');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Tableau');
    }

    public function testEditPrediction()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $predictionRepository = static::getContainer()->get(PredictionRepository::class);
        $eventRepository = static::getContainer()->get(EventRepository::class);
        $event = $eventRepository->findOneBy(['round' => 1, 'season' => 2022]);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $prediction = $predictionRepository->findOneBy(['user' => $testUser, 'event' => $event]);
        $client->request('GET', '/prediction/edit/'.$prediction->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Calendrier');
        
    }

}