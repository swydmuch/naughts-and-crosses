<?php
declare(strict_types=1);
namespace NAC\Domain\Field;

interface FieldInterface
{
    public function getCoordinateX(): int;

    public function getCoordinateY(): int;

    public function sameSymbol(FieldInterface $fieldToCompare): bool;
}
