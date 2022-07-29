<?php

namespace App\Tests\Front;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $crawler = $client->request('GET', '/register');
        $client->submitForm('Enregistrer', [
            'login_form[email]' => 'testUser@test.com',
            'login_form[personaname]' => 'TestUser',
            'login_form[password][first]' => 'fakePassword',
            'login_form[password][second]' => 'fakePassword',
        ]);
        $testUser = $userRepository->findOneByEmail('testUser@test.com');
        $this->assertInstanceOf(User::class, $testUser);
        $this->assertResponseRedirects('/login');
    }

    public function testLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->submitForm('Se connecter', [
            'email' => 'testUser@test.com',
            'password' => 'fakePassword'
        ]);
        $this->assertResponseRedirects('/home');
    }

    public function testProfilePage(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('testUser@test.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/profile');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Editer mon profil');
    }

    public function testRedirectIfNotAuthenticated(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile');

        $this->assertResponseRedirects('/login');
    }

    public function testEditProfile(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('testUser@test.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/profile');
        $client->submitForm('Enregistrer', [
            'login_form[email]' => 'testUserModified@test.com',
            'login_form[personaname]' => 'testUserModified',
            'login_form[password][first]' => 'fakeModifiedPassword',
            'login_form[password][second]' => 'fakeModifiedPassword',
        ]);

        $this->assertResponseIsSuccessful();
    }
}
