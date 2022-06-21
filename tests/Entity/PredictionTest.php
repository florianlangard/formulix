<?php

namespace App\Tests\Entity;

use App\Entity\Driver;
use App\Entity\Event;
use App\Entity\Prediction;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class PredictionTest extends TestCase
{
    public function testIsTrue(): void
    {
        $prediction = new Prediction();
        $event = new Event();
        $user = new User();
        $driver = new Driver();
        $date = new DateTime();

        $prediction
        ->setEvent($event)
        ->setUser($user)
        ->setTime('1:23.456')
        ->setPole($driver)
        ->setCreatedAt($date)
        ->setUpdatedAt($date)
        ->setFinishFirst($driver)
        ->setFinishSecond($driver)
        ->setFinishThird($driver)
        ->setRaceCreatedAt($date)
        ->setRaceUpdatedAt($date)
        ->setScore(1)
        ->setRaceScore(2)
        ->setTotalScore(3);

        $this->assertTrue($prediction->getEvent() === $event);
        $this->assertTrue($prediction->getUser() === $user); 
        $this->assertTrue($prediction->getTime() === '1:23.456'); 
        $this->assertTrue($prediction->getPole() === $driver); 
        $this->assertTrue($prediction->getCreatedAt() === $date); 
        $this->assertTrue($prediction->getUpdatedAt() === $date); 
        $this->assertTrue($prediction->getFinishFirst() === $driver); 
        $this->assertTrue($prediction->getFinishSecond() === $driver); 
        $this->assertTrue($prediction->getFinishThird() === $driver); 
        $this->assertTrue($prediction->getRaceCreatedAt() === $date); 
        $this->assertTrue($prediction->getRaceUpdatedAt() === $date); 
        $this->assertTrue($prediction->getScore() === 1); 
        $this->assertTrue($prediction->getRaceScore() === 2); 
        $this->assertTrue($prediction->getTotalScore() === 3);
    }

    public function testIsFalse(): void
    {
        $prediction = new Prediction();
        $event = new Event();
        $user = new User();
        $driver = new Driver();
        $date = new DateTime();

        $prediction
        ->setEvent($event)
        ->setUser($user)
        ->setTime('1:23.456')
        ->setPole($driver)
        ->setCreatedAt($date)
        ->setUpdatedAt($date)
        ->setFinishFirst($driver)
        ->setFinishSecond($driver)
        ->setFinishThird($driver)
        ->setRaceCreatedAt($date)
        ->setRaceUpdatedAt($date)
        ->setScore(1)
        ->setRaceScore(2)
        ->setTotalScore(3);

        $this->assertFalse($prediction->getTime() === '1:22.222');
        $this->assertFalse($prediction->getScore() === 2); 
        $this->assertFalse($prediction->getRaceScore() === 6); 
        $this->assertFalse($prediction->getTotalScore() === 9);
    }

    public function testIsEmpty(): void
    {
        $prediction = new Prediction();

        $this->assertEmpty($prediction->getEvent());
        $this->assertEmpty($prediction->getUser()); 
        $this->assertEmpty($prediction->getTime()); 
        $this->assertEmpty($prediction->getPole()); 
        $this->assertEmpty($prediction->getCreatedAt()); 
        $this->assertEmpty($prediction->getUpdatedAt()); 
        $this->assertEmpty($prediction->getFinishFirst()); 
        $this->assertEmpty($prediction->getFinishSecond()); 
        $this->assertEmpty($prediction->getFinishThird()); 
        $this->assertEmpty($prediction->getRaceCreatedAt()); 
        $this->assertEmpty($prediction->getRaceUpdatedAt()); 
        $this->assertEmpty($prediction->getScore()); 
        $this->assertEmpty($prediction->getRaceScore()); 
        $this->assertEmpty($prediction->getTotalScore());
        $this->assertEmpty($prediction->getId());
    }
}
