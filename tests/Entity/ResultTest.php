<?php

namespace App\Tests\Entity;

use App\Entity\Driver;
use App\Entity\Event;
use App\Entity\Result;
use DateTime;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testIsTrue(): void
    {
        $result = new Result();
        $driver = new Driver();
        $event = new Event();
        $date = new DateTime();

        $result
            ->setPole($driver)
            ->setEvent($event)
            ->setTime('1:23:456')
            ->setUpdatedAt($date)
            ->setFinishedFirst($driver)
            ->setFinishedSecond($driver)
            ->setFinishedThird($driver);
        
        $this->assertTrue($result->getPole() === $driver);
        $this->assertTrue($result->getEvent() === $event);
        $this->assertTrue($result->getTime() === '1:23:456');
        $this->assertTrue($result->getUpdatedAt() === $date);
        $this->assertTrue($result->getFinishedFirst() === $driver);
        $this->assertTrue($result->getFinishedSecond() === $driver);
        $this->assertTrue($result->getFinishedThird() === $driver);
    }

    public function testIsFalse(): void
    {
        $result = new Result();

        $result->setTime('1:23:456');
            
        $this->assertFalse($result->getTime() === '1:22:222');
    }

    public function testIsEmpty(): void
    {
        $result = new Result();

        $this->assertEmpty($result->getPole());
        $this->assertEmpty($result->getEvent());
        $this->assertEmpty($result->getTime());
        $this->assertEmpty($result->getUpdatedAt());
        $this->assertEmpty($result->getFinishedFirst());
        $this->assertEmpty($result->getFinishedSecond());
        $this->assertEmpty($result->getFinishedThird());
        $this->assertEmpty($result->getId());
    }
}
