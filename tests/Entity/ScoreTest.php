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
            ->setTotal(5);

        $this->assertTrue($score->getSeason() === '2022');
        $this->assertTrue($score->getUser() === $user);
        $this->assertTrue($score->getLastEvent() === $event);
        $this->assertTrue($score->getQualifyingScore() === 2);
        $this->assertTrue($score->getRaceScore() === 3);
        $this->assertTrue($score->getTotal() === 5);
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
            ->setTotal(5);

        $this->assertFalse($score->getSeason() === '2020');
        $this->assertFalse($score->getQualifyingScore() === 1);
        $this->assertFalse($score->getRaceScore() === 2);
        $this->assertFalse($score->getTotal() === 3);
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
    }
}
