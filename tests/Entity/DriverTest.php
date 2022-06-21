<?php

namespace App\Tests\Entity;

use App\Entity\Driver;
use PHPUnit\Framework\TestCase;

class DriverTest extends TestCase
{
    public function testIsTrue(): void
    {
        $driver = new Driver();

        $driver
            ->setFullname('test driver')
            ->setNumber(1)
            ->setDriverId('driver0')
            ->setCode('DR1')
            ->setIsActive(true);

        $this->assertTrue($driver->getFullname() === 'test driver');
        $this->assertTrue($driver->getNumber() === 1);
        $this->assertTrue($driver->getDriverId() === 'driver0');
        $this->assertTrue($driver->getCode() === 'DR1');
        $this->assertTrue($driver->getIsActive() === true);
    }

    public function testIsFalse(): void
    {
        $driver = new Driver();

        $driver
            ->setFullname('test driver')
            ->setNumber(1)
            ->setDriverId('driver0')
            ->setCode('DR1')
            ->setIsActive(true);

        $this->assertFalse($driver->getFullname() === 'false driver');
        $this->assertFalse($driver->getNumber() === 99);
        $this->assertFalse($driver->getDriverId() === 'false_driver');
        $this->assertFalse($driver->getCode() === 'FDR');
        $this->assertFalse($driver->getIsActive() === false);
    }

    public function testIsEmpty(): void
    {
        $driver = new Driver();

        $this->assertEmpty($driver->getFullname());
        $this->assertEmpty($driver->getNumber());
        $this->assertEmpty($driver->getDriverId());
        $this->assertEmpty($driver->getCode());
        $this->assertEmpty($driver->getIsActive());
        $this->assertEmpty($driver->getId());
    }
}
