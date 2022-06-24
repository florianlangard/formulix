<?php

namespace App\Tests\Services;

use App\Service\FormatConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormatConverterTest extends KernelTestCase
{
    public function testFormatTimeString(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $formatConverter = $container->get(FormatConverter::class);
        $convertedTime = $formatConverter->formatTimeString(1, 22, 333);
        $this->assertEquals('1:22.333',$convertedTime);
    }

    public function testUnformatTimeString(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $formatConverter = $container->get(FormatConverter::class);
        $unformattedTimeString = $formatConverter->unformatTimeString('1:22.333');
        $this->assertEquals(['minute' => 1, 'seconds' => 22, 'milliseconds' => 333],$unformattedTimeString);
    }

    public function testConvertToMs(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $formatConverter = $container->get(FormatConverter::class);
        $unformattedTimeString = $formatConverter->convertToMs(1, 20, 100);
        $this->assertEquals(80100 ,$unformattedTimeString);
    }
}
