<?php
declare(strict_types=1);
namespace NAC\Tests\Domain;

use NAC\Domain\Field\Cross;
use NAC\Domain\Field\InvalidCoordinateException;
use NAC\Domain\Field\Naught;
use NAC\Domain\Field\Position;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    public function testGettingCoordinate()
    {
        $coordinateX = 0;
        $coordinateY = 1;
        $position = new Position($coordinateX, $coordinateY);
        $this->assertSame($coordinateX, $position->getCoordinateX());
        $this->assertSame($coordinateY, $position->getCoordinateY());
    }

    public function testNegativeXCoordinate()
    {
        $this->expectException(InvalidCoordinateException::class);
        new Position(-1, 0);
    }

    public function testNegativeYCoordinate()
    {
        $this->expectException(InvalidCoordinateException::class);
        new Position(0, -1);
    }
}
