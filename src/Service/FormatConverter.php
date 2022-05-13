<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FormatConverter
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function formatTimeString(int $min, int $sec, int $msec) : string
    {
        $convertedTime = $min . ":" . $sec . "." . $msec;
        return $convertedTime;
    }

    public function unformatTimeString(string $timeString) : array
    {
        $min = explode(':', $timeString);
        $minute = $min[0];
        $sec = explode('.', $min[1]);
        $seconds = $sec[0];
        $milliseconds = $sec[1];
        return [$minute, $seconds, $milliseconds];
    }

    public function convertToMs(int $min, int $sec, int $msec) : int
    {
        $convertedTime = ($min * 60000) + ($sec * 1000) + $msec;
        return $convertedTime;
    }
}