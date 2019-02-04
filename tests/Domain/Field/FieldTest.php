<?php
declare(strict_types=1);
namespace NAC\Tests\Domain;

use NAC\Domain\Field\Cross;
use NAC\Domain\Field\InvalidCoordinateException;
use NAC\Domain\Field\Naught;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    public function testGettingCoordinate()
    {
        $coordinateX = 0;
        $coordinateY = 1;
        $naught = new Naught($coordinateX, $coordinateY);
        $this->assertSame($coordinateX, $naught->getCoordinateX());
        $this->assertSame($coordinateY, $naught->getCoordinateY());
    }

    public function testNegativeXCoordinate()
    {
        $this->expectException(InvalidCoordinateException::class);
        new Naught(-1, 0);
    }

    public function testNegativeYCoordinate()
    {
        $this->expectException(InvalidCoordinateException::class);
        new Naught(0, -1);
    }

    public function testDifferentSymbolComparision()
    {
        $nught = new Naught(0, 0);
        $cross = new Cross(1, 1);
        $this->assertFalse($nught->sameSymbol($cross));
    }

    public function testSameSymbolComparision()
    {
        $firstNaught = new Naught(0, 0);
        $secondNaught = new Naught(1, 1);
        $this->assertTrue($firstNaught->sameSymbol($secondNaught));
    }
}
