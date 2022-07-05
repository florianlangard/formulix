<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Score;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ScoreTest extends TestCase
{
    public function testIsTrue(): void
    {
        $score = new Score();
        $user = new User();
        $event = new Event();

        $score 
            ->setSeason('2022')
            ->setUser($user)
            ->setLastEvent($event)
            ->setQualifyingScore(2)
            ->setRaceScore(3)
            ->setTotal(5)
            ->setQualifyingWins(1)
            ->setQualifyingSecond(1)
            ->setQualifyingThird(1)
            ->setRaceWins(1)
            ->setRaceSecond(1)
            ->setRaceThird(1)
            ->setEventWins(1)
            ->setEventSecond(1)
            ->setEventThird(1);

        $this->assertTrue($score->getSeason() === '2022');
        $this->assertTrue($score->getUser() === $user);
        $this->assertTrue($score->getLastEvent() === $event);
        $this->assertTrue($score->getQualifyingScore() === 2);
        $this->assertTrue($score->getRaceScore() === 3);
        $this->assertTrue($score->getTotal() === 5);
        $this->assertTrue($score->getQualifyingWins() === 1);
        $this->assertTrue($score->getQualifyingSecond() === 1);
        $this->assertTrue($score->getQualifyingThird() === 1);
        $this->assertTrue($score->getRaceWins() === 1);
        $this->assertTrue($score->getRaceSecond() === 1);
        $this->assertTrue($score->getRaceThird() === 1);
        $this->assertTrue($score->getEventWins() === 1);
        $this->assertTrue($score->getEventSecond() === 1);
        $this->assertTrue($score->getEventThird() === 1);
    }

    public function testIsFalse(): void
    {
        $score = new Score();
        $user = new User();
        $event = new Event();

        $score 
            ->setSeason('2022')
            ->setUser($user)
            ->setLastEvent($event)
            ->setQualifyingScore(2)
            ->setRaceScore(3)
            ->setTotal(5)
            ->setQualifyingWins(1)
            ->setQualifyingSecond(1)
            ->setQualifyingThird(1)
            ->setRaceWins(1)
            ->setRaceSecond(1)
            ->setRaceThird(1)
            ->setEventWins(1)
            ->setEventSecond(1)
            ->setEventThird(1);

        $this->assertFalse($score->getSeason() === '2020');
        $this->assertFalse($score->getQualifyingScore() === 1);
        $this->assertFalse($score->getRaceScore() === 2);
        $this->assertFalse($score->getTotal() === 3);
        $this->assertFalse($score->getQualifyingWins() === 2);
        $this->assertFalse($score->getQualifyingSecond() === 2);
        $this->assertFalse($score->getQualifyingThird() === 2);
        $this->assertFalse($score->getRaceWins() === 2);
        $this->assertFalse($score->getRaceSecond() === 2);
        $this->assertFalse($score->getRaceThird() === 2);
        $this->assertFalse($score->getEventWins() === 2);
        $this->assertFalse($score->getEventSecond() === 2);
        $this->assertFalse($score->getEventThird() === 2);
    }

    public function testIsEmpty(): void
    {
        $score = new Score();

        $this->assertEmpty($score->getSeason());
        $this->assertEmpty($score->getUser());
        $this->assertEmpty($score->getLastEvent());
        $this->assertEmpty($score->getQualifyingScore());
        $this->assertEmpty($score->getRaceScore());
        $this->assertEmpty($score->getTotal());
        $this->assertEmpty($score->getId());
        $this->assertEmpty($score->getQualifyingWins());
        $this->assertEmpty($score->getQualifyingSecond());
        $this->assertEmpty($score->getQualifyingThird());
        $this->assertEmpty($score->getRaceWins());
        $this->assertEmpty($score->getRaceSecond());
        $this->assertEmpty($score->getRaceThird());
        $this->assertEmpty($score->getEventWins());
        $this->assertEmpty($score->getEventSecond());
        $this->assertEmpty($score->getEventThird());
    }
}
