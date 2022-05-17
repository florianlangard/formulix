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

    /**
     * Turns qualifying time submitted in form into string.
     *
     * @param integer $min
     * @param integer $sec
     * @param integer $msec
     * @return string
     */
    public function formatTimeString(int $min, int $sec, int $msec) : string
    {
        $convertedTime = $min . ":" . $sec . "." . $msec;
        return $convertedTime;
    }

    /**
     * Unformats prediction timeString to array.
     *
     * @param string $timeString
     * @return array
     */
    public function unformatTimeString(string $timeString) : array
    {
        $time = explode(':', $timeString);
        $minute = intval($time[0]);
        $sec = explode('.', $time[1]);
        $seconds = intval($sec[0]);
        $milliseconds = intval($sec[1]);
        return [
            'minute' => $minute, 
            'seconds' => $seconds, 
            'milliseconds' => $milliseconds
        ];
    }

    /**
     * Converts time in milliseconds for easier score calculation.
     *
     * @param integer $min
     * @param integer $sec
     * @param integer $msec
     * @return integer
     */
    public function convertToMs(int $min, int $sec, int $msec) : int
    {
        $convertedTime = ($min * 60000) + ($sec * 1000) + $msec;
        return $convertedTime;
    }
}