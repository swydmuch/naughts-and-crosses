<?php
declare(strict_types=1);

namespace NAC\Domain\Field;

class Position
{
    private $coordinateX;
    private $coordinateY;
    public function __construct(int $coordinateX, int $coordinateY)
    {
        if ($coordinateX < 0) {
            throw new InvalidCoordinateException('Coordinate X can not be negative');
        }

        if ($coordinateY < 0) {
            throw new InvalidCoordinateException('Coordinate Y can not be negative');
        }

        $this->coordinateX = $coordinateX;
        $this->coordinateY = $coordinateY;
    }

    public function getCoordinateX(): int
    {
        return $this->coordinateX;
    }

    public function getCoordinateY(): int
    {
        return $this->coordinateY;
    }

}