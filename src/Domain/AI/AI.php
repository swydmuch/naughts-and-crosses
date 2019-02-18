<?php
declare(strict_types=1);
namespace NAC\Domain\AI;


use NAC\Domain\Board\AlreadyFinishedGameException;
use NAC\Domain\Board\BoardInterface;
use NAC\Domain\Field\Cross;
use NAC\Domain\Field\Naught;

/**
 * Proposes next move on board, based on MinMax algorithm
 */
class AI
{
    private $board;
    const VALUE_FOR_VICTORY = 1;
    const VALUE_FOR_LOSE = -1;
    const VALUE_FOR_DRAW = 0;

    public function __construct(BoardInterface $board)
    {
        $this->board = $board;
    }


    public function evaluateNextPosition(): array
    {
        $notTakenPositions = $this->board->getNotTakenPositions();
        if (empty($notTakenPositions)) {
            throw new AlreadyFinishedGameException();
        }

        $maxPosition = null;
        $maxValue = null;
        foreach ($notTakenPositions as $eachPosition) {
            $isAIMoves = true;
            $value = $this->getValueForPosition($this->board, $eachPosition, $isAIMoves, $notTakenPositions);
            if (is_null($maxValue) || $value > $maxValue) {
                $maxValue = $value;
                $maxPosition = $eachPosition;
            }
        }

        return $maxPosition;
    }

    private function getValueForPosition(BoardInterface $board, array $position, bool $isAIMoves, array $notTakenPositions): int
    {
        $copyOfBoard = clone $board;
        if ($copyOfBoard->isCrossMove()) {
            $field = new Cross($position[0], $position[1]);
        } else {
            $field = new Naught($position[0], $position[1]);
        }

        $copyOfBoard->putField($field);

        $isVictory = $copyOfBoard->isVictory();
        $isDraw = $copyOfBoard->isDraw();

        if ($isVictory) {
            return $isAIMoves ? self::VALUE_FOR_VICTORY : self::VALUE_FOR_LOSE;
        } elseif ($isDraw) {
            return self::VALUE_FOR_DRAW;
        } else {
            foreach ($notTakenPositions as $positionKey => $positionValue) {
                if ($positionValue === $position) {
                    unset($notTakenPositions[$positionKey]);
                }
            }

            $isAIMoves = !$isAIMoves;
            if ($isAIMoves) {
                $maxValue = null;
                foreach ($notTakenPositions as $eachPosition) {
                    $value =  $this->getValueForPosition($copyOfBoard, $eachPosition, $isAIMoves, $notTakenPositions);
                    if (is_null($maxValue) || $value > $maxValue) {
                        $maxValue = $value;
                    }
                }

                return $maxValue;
            } else {
                $minValue = null;
                foreach ($notTakenPositions as $eachPosition) {
                    $value =  $this->getValueForPosition($copyOfBoard, $eachPosition, $isAIMoves, $notTakenPositions);
                    if (is_null($minValue) || $value < $minValue) {
                        $minValue = $value;
                    }
                }

                return $minValue;
            }
        }
    }

}
