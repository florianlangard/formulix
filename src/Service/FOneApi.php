<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FOneApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchSeasonSchedule($year)
    {
        $response = $this->client->request(
            'GET',
            'https://ergast.com/api/f1/'.$year.'.json'
        );
        $content = $response->toArray();
        return $content;
    }

    public function fetchSeasonDrivers($year)
    {
        $response = $this->client->request(
            'GET',
            'https://ergast.com/api/f1/'.$year.'/drivers.json'
            // 'https://ergast.com/api/f1/'.$year.'/driverStandings.json'
        );
        $content = $response->toArray();
        return $content;
    }

    public function fetchQualifyingResults($year, $round)
    {
        $response  = $this->client->request(
            'GET',
            'https://ergast.com/api/f1/'.$year.'/'.$round.'/qualifying.json'
        );
        $content = $response->toArray();
        return $content;
    }

    public function fetchRaceResults($year, $round)
    {
        $response  = $this->client->request(
            'GET',
            'https://ergast.com/api/f1/'.$year.'/'.$round.'/results.json'
        );
        $content = $response->toArray();
        return $content;
    }
}