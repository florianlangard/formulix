<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StatsControllerTest extends WebTestCase {

    public function testStatsPage() {
        $client = static::createClient();
        $client->request('GET', '/stats');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1StatsPage() {
        $client = static::createClient();
        $client->request('GET', '/stats');
        $this->assertSelectorTextContains('h1', 'Statistiques');
    }

    public function testRulesPage() {
        $client = static::createClient();
        $client->request('GET', '/rules');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}