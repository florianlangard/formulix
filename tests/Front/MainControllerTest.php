<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MainControllerTest extends WebTestCase {

    public function testLandingpage() {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testHomepage() {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1Homepage() {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertSelectorTextContains('h1', 'Tableau de Bord');
    }

}