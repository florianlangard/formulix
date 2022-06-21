<?php

namespace App\Tests\Entity;

use App\Entity\Driver;
use App\Entity\Event;
use App\Entity\Podium;
use App\Entity\Prediction;
use PHPUnit\Framework\TestCase;

class PodiumTest extends TestCase
{
    public function testIsTrue(): void
    {
        $podium = new Podium();
        $event = new Event();
        $prediction = new Prediction();

        $podium
        ->setEvent($event)
        ->setQualifyingFirst($prediction)
        ->setQualifyingSecond($prediction)
        ->setQualifyingThird($prediction)
        ->setRaceFirst($prediction)
        ->setRaceSecond($prediction)
        ->setRaceThird($prediction)
        ->setEventFirst($prediction)
        ->setEventSecond($prediction)
        ->setEventThird($prediction);

        $this->assertTrue($podium->getEvent() === $event);
        $this->assertTrue($podium->getQualifyingFirst() === $prediction); 
        $this->assertTrue($podium->getQualifyingSecond() === $prediction); 
        $this->assertTrue($podium->getQualifyingThird() === $prediction); 
        $this->assertTrue($podium->getRaceFirst() === $prediction); 
        $this->assertTrue($podium->getRaceSecond() === $prediction); 
        $this->assertTrue($podium->getRaceThird() === $prediction); 
        $this->assertTrue($podium->getEventFirst() === $prediction); 
        $this->assertTrue($podium->getEventSecond() === $prediction); 
        $this->assertTrue($podium->getEventThird() === $prediction); 
    }

    public function testIsEmpty(): void
    {
        $podium = new Podium();

        $this->assertEmpty($podium->getEvent());
        $this->assertEmpty($podium->getQualifyingFirst()); 
        $this->assertEmpty($podium->getQualifyingSecond()); 
        $this->assertEmpty($podium->getQualifyingThird());
        $this->assertEmpty($podium->getRaceFirst()); 
        $this->assertEmpty($podium->getRaceSecond()); 
        $this->assertEmpty($podium->getRaceThird()); 
        $this->assertEmpty($podium->getEventFirst()); 
        $this->assertEmpty($podium->getEventSecond()); 
        $this->assertEmpty($podium->getEventThird());
        $this->assertEmpty($podium->getId());
    }
}
