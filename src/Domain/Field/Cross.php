<?php
declare(strict_types=1);
namespace NAC\Domain\Field;

class Cross extends AbstractField
{
    public function __construct(int $coordinateX, int $coordinateY)
    {
        parent::__construct($coordinateX, $coordinateY);
    }
}
