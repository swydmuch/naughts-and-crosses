<?php
declare(strict_types=1);
namespace NAC\Domain\Board;

use NAC\Domain\Field\Cross;
use NAC\Domain\Field\FieldInterface;
use NAC\Domain\Field\InvalidCoordinateException;
use NAC\Domain\Field\Naught;
use NAC\Domain\Field\Position;
use NAC\Domain\Field\PositionCollection;

class Board implements BoardInterface
{
    const LEFT_DIRECTION = 'LEFT';
    const RIGHT_DIRECTION = 'RIGHT';
    const TOP_DIRECTION = 'TOP';
    const BOTTOM_DIRECTION = 'BOTTOM';
    const LEFT_BOTTOM_DIRECTION = 'LEFT_BOTTOM';
    const LEFT_TOP_DIRECTION = 'LEFT_TOP';
    const RIGHT_BOTTOM_DIRECTION = 'RIGHT_BOTTOM';
    const RIGHT_TOP_DIRECTION = 'RIGHT_TOP';

    const CROSS_PLAYER = 1;
    const NAUGHT_PLAYER = 2;

    private $size;
    /** @var FieldInterface[]  */
    private $fields;
    private $winingLineSize;
    private $winningLineFields;
    private $isVictory;
    private $isDraw;
    private $nextPlayer;
    private $identifier;

    public function __construct(int $size, int $winingLineSize, int $startingPlayer, string $identifier)
    {
        if ($size < 3) {
            throw new TooSmallBoardSizeException();
        }

        if ($winingLineSize > $size) {
            throw new LineSizeBiggerThanBoardException();
        }

        if ($startingPlayer <> self::CROSS_PLAYER && $startingPlayer <> self::NAUGHT_PLAYER) {
            throw new WrongPlayerException();
        }

        if (strlen($identifier) != 13) {
            throw new WrongIdentifierException();
        }


        $this->size = $size;
        $this->fields = [];
        $this->winingLineSize = $winingLineSize;
        $this->winningLineFields = [];
        $this->isVictory = false;
        $this->isDraw = false;
        $this->nextPlayer = $startingPlayer;
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }



    public function take(Position $position): void
    {
        if ($this->isCrossMove()) {
            $field = new Cross($position->getCoordinateX(), $position->getCoordinateY());
            $this->nextPlayer = self::NAUGHT_PLAYER;
        } else {
            $field = new Naught($position->getCoordinateX(), $position->getCoordinateY());
            $this->nextPlayer = self::CROSS_PLAYER;
        }

        $this->putField($field);
    }

    public function putField(FieldInterface $field): void
    {
        $this->throwExceptionIfFieldPositionNotValid($field);
        $this->throwExceptionIfFieldPositionNotFree($field);
        $this->throwExceptionIfGameAlreadyFinished();
        $this->addFieldToCollection($field);
        $this->evaluateVictory($field);
        $this->evaluateDraw();
    }

    public function isVictory(): bool
    {
        return $this->isVictory;
    }

    public function isDraw(): bool
    {
        return $this->isDraw;
    }

    public function getWinningFields() : array
    {
        return $this->winningLineFields;
    }

    public function isTakenPosition($coordinateX, $coordinateY): bool
    {
        $this->throwExceptionIfCoordinateNotValid($coordinateX);
        $this->throwExceptionIfCoordinateNotValid($coordinateY);
        return isset($this->fields[$coordinateX][$coordinateY]);
    }

    public function getFieldByXY($coordinateX, $coordinateY): FieldInterface
    {
        $this->throwExceptionIfCoordinateNotValid($coordinateX);
        $this->throwExceptionIfCoordinateNotValid($coordinateY);
        return $this->fields[$coordinateX][$coordinateY];
    }

    public function getEmptyPositions(): PositionCollection
    {
        $emptyPositions = new PositionCollection();
        for ($coordinateX = 0; $coordinateX < $this->size; $coordinateX++) {
            for ($coordinateY = 0; $coordinateY < $this->size; $coordinateY++) {
                if (!$this->isTakenPosition($coordinateX, $coordinateY)) {
                    $emptyPositions->attach(new Position($coordinateX, $coordinateY));
                }
            }
        }
        return $emptyPositions;
    }

    public function isCrossMove(): bool
    {
        return $this->nextPlayer === self::CROSS_PLAYER;
    }

    public function isNaughtMove(): bool
    {
        return $this->nextPlayer === self::NAUGHT_PLAYER;
    }

    private function addFieldToCollection(FieldInterface $field)
    {
        $this->fields[$field->getCoordinateX()][$field->getCoordinateY()] = $field;
    }

    private function evaluateVictory(FieldInterface $field)
    {
        $neighbours = [
            $this->getTopNeighbours($field),
            $this->getBottomNeighbours($field),
            $this->getLeftNeighbours($field),
            $this->getRightNeighbours($field),
            $this->getLeftBottomNeighbours($field),
            $this->getLeftTopNeighbours($field),
            $this->getRightTopNeighbours($field),
            $this->getRightBottomNeighbours($field)
        ];

        foreach ($neighbours as $eachDirectionNeighbours) {
            $neighboursCount = count($eachDirectionNeighbours);
            if ($neighboursCount >= ($this->winingLineSize - 1)) {
                $winningFields[] = $field;
                foreach ($eachDirectionNeighbours as $eachField) {
                    $winningFields[] = $eachField;
                }

                $this->isVictory = true;
                $this->winningLineFields = $winningFields;
            }
        }
    }

    private function evaluateDraw()
    {
        $this->isDraw = ($this->isVictory === false && count($this->getEmptyPositions()) === 0);
    }

    private function getTopNeighbours(FieldInterface $field) : array
    {
        return $this->getNeighboursCollectionByDirection($field, self::TOP_DIRECTION);
    }

    private function getBottomNeighbours(FieldInterface $field) : array
    {
        return $this->getNeighboursCollectionByDirection($field, self::BOTTOM_DIRECTION);
    }

    private function getLeftNeighbours(FieldInterface $field) : array
    {
        return $this->getNeighboursCollectionByDirection($field, self::LEFT_DIRECTION);
    }

    private function getRightNeighbours(FieldInterface $field) : array
    {
        return $this->getNeighboursCollectionByDirection($field, self::RIGHT_DIRECTION);
    }

    private function getLeftBottomNeighbours(FieldInterface $field) : array
    {
        return $this->getNeighboursCollectionByDirection($field, self::LEFT_BOTTOM_DIRECTION);
    }

    private function getRightTopNeighbours(FieldInterface $field) : array
    {
        return $this->getNeighboursCollectionByDirection($field, self::RIGHT_TOP_DIRECTION);
    }

    private function getLeftTopNeighbours(FieldInterface $field) : array
    {
        return $this->getNeighboursCollectionByDirection($field, self::LEFT_TOP_DIRECTION);
    }

    private function getRightBottomNeighbours(FieldInterface $field) : array
    {
        return $this->getNeighboursCollectionByDirection($field, self::RIGHT_BOTTOM_DIRECTION);
    }

    private function getNeighboursCollectionByDirection(FieldInterface $field, string $direction): array
    {
        $result = [];
        $foundNeighbour = $this->getNeighbourByDirection($field, $direction);
        if (isset($foundNeighbour)) {
            $result[] = $foundNeighbour;
            $nextNeighbours = $this->getNeighboursCollectionByDirection($foundNeighbour, $direction);
            if (!empty($nextNeighbours)) {
                $result[] = $nextNeighbours[0];
            }
        }

        return $result;
    }

    private function getNeighbourByDirection(FieldInterface $field, string $direction): ?FieldInterface
    {
        $neighbourCoordinateX = $this->getCoordinateXForNeighbour($field, $direction);
        $neighbourCoordinateY = $this->getCoordinateYForNeighbour($field, $direction);

        $foundNeighbour = null;
        if ($this->isValidCoordinate($neighbourCoordinateX)
            && $this->isValidCoordinate($neighbourCoordinateY)
            && $this->isPositionTakenBySameSymbol($field, $neighbourCoordinateX, $neighbourCoordinateY)
            ) {
            $foundNeighbour = $this->getFieldByXY($neighbourCoordinateX, $neighbourCoordinateY);
        }

        return $foundNeighbour;
    }

    private function getCoordinateXForNeighbour(FieldInterface $field, string $direction): int
    {
        $neighbourCoordinateX = $field->getCoordinateX();
        if ($this->isLeftDirection($direction)) {
            $neighbourCoordinateX--;
        } elseif ($this->isRightDirection($direction)) {
            $neighbourCoordinateX++;
        }

        return $neighbourCoordinateX;
    }

    private function getCoordinateYForNeighbour(FieldInterface $field, string $direction): int
    {
        $neighbourCoordinateY = $field->getCoordinateY();
        if ($this->isTopDirection($direction)) {
            $neighbourCoordinateY++;
        } elseif ($this->isBottomDirection($direction)) {
            $neighbourCoordinateY--;
        }

        return $neighbourCoordinateY;
    }

    private function isPositionTakenBySameSymbol(FieldInterface $baseField, int $coordinateX, int $coordinateY): bool
    {
        if ($this->isTakenPosition($coordinateX, $coordinateY)) {
            $field = $this->getFieldByXY($coordinateX, $coordinateY);
            if ($baseField->sameSymbol($field)) {
                return true;
            }
        }

        return false;
    }

    private function isTopDirection(string $direction): bool
    {
        return ($direction  == self::TOP_DIRECTION
            || $direction == self::RIGHT_TOP_DIRECTION
            || $direction == self::LEFT_TOP_DIRECTION);
    }

    private function isBottomDirection(string $direction): bool
    {
        return ($direction == self::BOTTOM_DIRECTION
            || $direction == self::LEFT_BOTTOM_DIRECTION
            || $direction == self::RIGHT_BOTTOM_DIRECTION);
    }

    private function isLeftDirection(string $direction): bool
    {
        return ($direction == self::LEFT_DIRECTION
            || $direction == self::LEFT_BOTTOM_DIRECTION
            || $direction == self::LEFT_TOP_DIRECTION);
    }

    private function isRightDirection(string $direction): bool
    {
        return ($direction == self::RIGHT_DIRECTION
            || $direction == self::RIGHT_TOP_DIRECTION
            || $direction == self::RIGHT_BOTTOM_DIRECTION);
    }

    private function isValidCoordinate(int $coordinate): bool
    {
        return ($coordinate >= 0 ) && ($coordinate < $this->size);
    }

    private function throwExceptionIfCoordinateNotValid(int $coordinate): void
    {
        if (!$this->isValidCoordinate($coordinate)) {
            throw new InvalidCoordinateException('Coordinate is not valid:' . $coordinate);
        }
    }

    private function throwExceptionIfFieldPositionNotValid(FieldInterface $fieldToCheck): void
    {
        $this->throwExceptionIfCoordinateNotValid($fieldToCheck->getCoordinateX());
        $this->throwExceptionIfCoordinateNotValid($fieldToCheck->getCoordinateY());
    }

    private function throwExceptionIfGameAlreadyFinished(): void
    {
        if ($this->isVictory() || $this->isDraw()) {
            throw new AlreadyFinishedGameException();
        }
    }

    private function throwExceptionIfFieldPositionNotFree(FieldInterface $fieldToCheck): void
    {
        if (isset($this->fields[$fieldToCheck->getCoordinateX()][$fieldToCheck->getCoordinateY()])) {
            throw new AlreadyTakenPositionException();
        }
    }
}
