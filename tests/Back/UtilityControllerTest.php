<?php

namespace App\Tests\Back;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtilityControllerTest extends WebTestCase {

    public function testBackofficeRedirects() {
        $client = static::createClient();
        $client->request('GET', '/back/user');
        $this->assertResponseRedirects('/login');
    }

    public function testBackofficeAdminAccess() {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/back/user/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'User index');
    }
    
    public function testBackofficeUserAccessDenied() {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user1@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/back/user/');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testCalculateScore()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/back/utility/calculate_scores');
        $this->assertResponseRedirects('/back/utility');
    }

}