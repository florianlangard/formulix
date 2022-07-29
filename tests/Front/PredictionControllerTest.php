<?php

namespace App\Tests\Front;

use App\Form\PredictionFormType;
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

    public function testQualifyingPrediction()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $eventRepository = static::getContainer()->get(EventRepository::class);
        $driverRepository = static::getContainer()->get(DriverRepository::class);
        $predictionRepository = static::getContainer()->get(PredictionRepository::class);
        $event = $eventRepository->findOneBy(['round' => 5, 'season' => 2022]);
        $driver = $driverRepository->findOneBy(['fullname' => 'prénom1 nom1']);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/prediction/add/qualifying/'.$event->getSlug());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Pronostics');

        // $buttonCrawlerNode = $crawler->selectButton('Enregistrer');
        // $form = $buttonCrawlerNode->form();

        $client->submitForm('Enregistrer', [
            'prediction_form[pole]' => $driver->getId(),
            'prediction_form[min]' => '1',
            'prediction_form[sec]' => '22',
            'prediction_form[msec]' => '789'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        // $prediction = $predictionRepository->findOneBy([], ['created_at' => 'DESC']);
        // $em->remove($prediction);
        // $em->flush();
    }

    public function testRacePrediction()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $eventRepository = static::getContainer()->get(EventRepository::class);
        $driverRepository = static::getContainer()->get(DriverRepository::class);
        $predictionRepository = static::getContainer()->get(PredictionRepository::class);
        $event = $eventRepository->findOneBy(['round' => 5, 'season' => 2022]);
        $drivers = $driverRepository->findBy(['fullname' => ['prénom1 nom1', 'prénom2 nom2', 'prénom3 nom3']]);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/prediction/add/race/'.$event->getSlug());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Pronostics');

        // $buttonCrawlerNode = $crawler->selectButton('Enregistrer');
        // $form = $buttonCrawlerNode->form();

        $client->submitForm('Enregistrer', [
            'race_prediction[finishFirst]' => $drivers[0]->getId(),
            'race_prediction[finishSecond]' => $drivers[1]->getId(),
            'race_prediction[finishThird]' => $drivers[2]->getId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testEditPrediction()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $predictionRepository = static::getContainer()->get(PredictionRepository::class);
        $eventRepository = static::getContainer()->get(EventRepository::class);
        $driverRepository = static::getContainer()->get(DriverRepository::class);
        $event = $eventRepository->findOneBy(['round' => 5, 'season' => 2022]);
        $driver = $driverRepository->findOneBy(['fullname' => 'prénom2 nom2']);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $prediction = $predictionRepository->findOneBy(['user' => $testUser, 'event' => $event]);
        // $client->request('GET', '/prediction/edit/'.$prediction->getId());
        $client->request('GET', '/prediction/add/qualifying/'.$event->getSlug());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $client->submitForm('Enregistrer', [
            'prediction_form[pole]' => $driver->getId(),
            'prediction_form[min]' => '1',
            'prediction_form[sec]' => '22',
            'prediction_form[msec]' => '789'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // $this->assertSelectorTextContains('h1', 'Calendrier');
        
    }

    public function testEditRacePrediction()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $predictionRepository = static::getContainer()->get(PredictionRepository::class);
        $eventRepository = static::getContainer()->get(EventRepository::class);
        $driverRepository = static::getContainer()->get(DriverRepository::class);
        $event = $eventRepository->findOneBy(['round' => 5, 'season' => 2022]);
        $drivers = $driverRepository->findBy(['fullname' => ['prénom4 nom4', 'prénom5 nom5', 'prénom6 nom6']]);
        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $prediction = $predictionRepository->findOneBy(['user' => $testUser, 'event' => $event]);
        // $client->request('GET', '/prediction/edit/'.$prediction->getId());
        $client->request('GET', '/prediction/add/race/'.$event->getSlug());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $client->submitForm('Enregistrer', [
            'race_prediction[finishFirst]' => $drivers[0]->getId(),
            'race_prediction[finishSecond]' => $drivers[1]->getId(),
            'race_prediction[finishThird]' => $drivers[2]->getId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // $this->assertSelectorTextContains('h1', 'Calendrier');
        
    }

}