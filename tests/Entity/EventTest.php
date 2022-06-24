<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Event;
use App\Entity\Podium;
use App\Entity\Score;
use App\Entity\Prediction;
use App\Entity\Result;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testIsTrue(): void
    {
        $event = new Event();
        $date = New DateTime();

        $event
            ->setName('test event')
            ->setDate($date)
            ->setRound('1')
            ->setSeason('2022')
            ->setCircuitName('test circuit')
            ->setLocality('test locality')
            ->setCountry('test country')
            ->setCountryCode('fr')
            ->setQualifyingDate($date)
            ->setSlug('test-event');

        $this->assertTrue($event->getName() === 'test event');
        $this->assertTrue($event->getDate() === $date);
        $this->assertTrue($event->getRound() === '1');
        $this->assertTrue($event->getSeason() === '2022');
        $this->assertTrue($event->getCircuitName() === 'test circuit');
        $this->assertTrue($event->getLocality() === 'test locality');
        $this->assertTrue($event->getCountry() === 'test country');
        $this->assertTrue($event->getCountryCode() === 'fr');
        $this->assertTrue($event->getQualifyingDate() === $date);
        $this->assertTrue($event->getSlug() === 'test-event');
    }

    public function testIsFalse(): void
    {
        $event = new Event();
        $date = New DateTime();

        $event
            ->setName('test event')
            ->setDate($date)
            ->setRound('1')
            ->setSeason('2022')
            ->setCircuitName('test circuit')
            ->setLocality('test locality')
            ->setCountry('test country')
            ->setCountryCode('fr')
            ->setQualifyingDate($date)
            ->setSlug('test-event');

        $this->assertFalse($event->getName() === 'false event');
        $this->assertFalse($event->getDate() === new DateTime());
        $this->assertFalse($event->getRound() === '0');
        $this->assertFalse($event->getSeason() === '2020');
        $this->assertFalse($event->getCircuitName() === 'false circuit');
        $this->assertFalse($event->getLocality() === 'false locality');
        $this->assertFalse($event->getCountry() === 'false country');
        $this->assertFalse($event->getCountryCode() === 'at');
        $this->assertFalse($event->getQualifyingDate() === new DateTime());
        $this->assertFalse($event->getSlug() === 'false-event');
    }

    public function testIsEmpty(): void
    {
        $event = new Event();
        $date = New DateTime();

        $this->assertEmpty($event->getName());
        $this->assertEmpty($event->getDate());
        $this->assertEmpty($event->getRound());
        $this->assertEmpty($event->getSeason());
        $this->assertEmpty($event->getCircuitName());
        $this->assertEmpty($event->getLocality());
        $this->assertEmpty($event->getCountry());
        $this->assertEmpty($event->getCountryCode());
        $this->assertEmpty($event->getQualifyingDate());
        $this->assertEmpty($event->getSlug());
        $this->assertEmpty($event->getPredictions());
        $this->assertEmpty($event->getScores());
        $this->assertEmpty($event->getPodium());
    }

    public function testGetRemovePrediction()
    {
        $event = new Event();
        $prediction = new Prediction();

        $this->assertEmpty($event->getPredictions());

        $event->addPrediction($prediction);
        $this->assertContains($prediction, $event->getPredictions());

        $event->removePrediction($prediction);
        $this->assertEmpty($event->getPredictions());
    }

    public function testGetAddRemoveScore()
    {
        $event = new Event();
        $score = new Score();

        $this->assertEmpty($event->getScores());

        $event->addScore($score);
        $this->assertContains($score, $event->getScores());

        $event->removeScore($score);
        $this->assertEmpty($event->getScores());
    }

    public function testGetSetResult()
    {
        $event = new Event();
        $result = new Result();

        $this->assertEmpty($event->getResult());

        $event->setResult($result);
        $this->assertEquals($result, $event->getResult());
    }

    public function testGetSetPodium()
    {
        $event = new Event();
        $podium = new Podium();

        $this->assertEmpty($event->getPodium());

        $event->setPodium($podium);
        $this->assertEquals($podium, $event->getPodium());
    }
}
