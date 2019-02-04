<?php
declare(strict_types=1);
namespace NAC\Domain\Board;

use NAC\Domain\Field\FieldInterface;

interface BoardInterface
{
    public function putField(FieldInterface $field): void;

    public function isTakenPosition($coordinateX, $coordinateY): bool;

    public function getWinningFields() : array;

    public function isVictory(): bool;

    public function isDraw(): bool;
}
