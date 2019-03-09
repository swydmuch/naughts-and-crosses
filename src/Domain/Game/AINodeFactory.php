<?php
declare(strict_types=1);

namespace NAC\Domain\Game;

use NAC\Domain\Board\BoardInterface;
use NAC\Domain\Field\Position;
use NAC\Domain\Field\PositionCollection;

class AINodeFactory extends AbstractNodeFactory
{
    protected function createChildrenFactory(BoardInterface $copyOfBoard, $copyOfEmptyPositions): AbstractNodeFactory
    {
        return new OpponentNodeFactory($copyOfBoard, $copyOfEmptyPositions);
    }

    protected function createNode(Position $eachPosition, BoardInterface $copyOfBoard): AbstractNode
    {
        return new AINode($eachPosition, $copyOfBoard);
    }
}