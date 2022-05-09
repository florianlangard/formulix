<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase {

    public function testDisplayLogin() {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    public function testLoginWithBadCredentials() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'admin@test.com',
            'password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfulLogin() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'admin@test.com',
            'password' => '$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        
    }

}