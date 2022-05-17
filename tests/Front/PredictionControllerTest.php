<?php

namespace App\Tests\Front;

use App\Repository\DriverRepository;
use App\Repository\UserRepository;
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
        $client->request('GET', '/prediction/add/qualifying/course-n-0');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Tableau de Bord');
    }

    public function testRedirectOnExpiredRaceEvent()
    {
        $client = static::createClient();
        $client->request('GET', '/prediction/add/race/course-n-0');
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

}