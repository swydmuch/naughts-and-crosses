<?php
declare(strict_types=1);
namespace NAC\Domain\Board;

use NAC\Domain\Field\FieldInterface;
use NAC\Domain\Field\Position;
use NAC\Domain\Field\PositionCollection;

interface BoardInterface
{
    public function take(Position $position): void;

    public function putField(FieldInterface $field): void;

    public function isTakenPosition($coordinateX, $coordinateY): bool;

    public function getWinningFields() : array;

    public function isVictory(): bool;

    public function isDraw(): bool;

    public function getNotTakenPositions(): array; //TODO to replace by getEmptyPositions

    public function getEmptyPositions(): PositionCollection;

    public function isCrossMove(): bool;

    public function isNaughtMove(): bool;
}
