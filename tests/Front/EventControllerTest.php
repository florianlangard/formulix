<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EventControllerTest extends WebTestCase {

    public function testEventspage() {
        $client = static::createClient();
        $client->request('GET', '/events');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1EventsPage() {
        $client = static::createClient();
        $client->request('GET', '/events');
        $this->assertSelectorTextContains('h1', 'Calendrier 2022');
    }

    public function testEventsInEventsPage() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/events');
        $this->assertCount(21, $crawler->filter('div .event.card'));
    }

}