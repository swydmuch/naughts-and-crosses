<?php
declare(strict_types=1);
namespace NAC\Domain\Field;

abstract class AbstractField implements FieldInterface
{
    protected $coordinateX;
    protected $coordinateY;
    
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

    public function sameSymbol(FieldInterface $fieldToCompare): bool
    {
        return get_class($this) === get_class($fieldToCompare);
    }
}
