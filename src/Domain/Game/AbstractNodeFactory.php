<?php
declare(strict_types=1);

namespace NAC\Domain\Game;

use NAC\Domain\Board\BoardInterface;
use NAC\Domain\Field\Position;
use NAC\Domain\Field\PositionCollection;

abstract class AbstractNodeFactory
{
    private $board;
    private $emptyPositions;

    public function __construct(BoardInterface $board, PositionCollection $emptyPositions)
    {
        $this->board = $board;
        $this->emptyPositions = $emptyPositions;
    }

    public function create(): NodeCollection
    {
        $nodeCollection = new NodeCollection();
        foreach ($this->emptyPositions as $eachPosition) {
            $copyOfBoard = clone $this->board;
            $copyOfEmptyPositions = clone $this->emptyPositions;
            $node = $this->createNode($eachPosition, $copyOfBoard);
            $copyOfEmptyPositions->detach($eachPosition);
            $factory = $this->createChildrenFactory($copyOfBoard, $copyOfEmptyPositions);
            $children = $factory->create();
            foreach ($children as $child) {
                $node->addChild($child);
            }

            $nodeCollection->attach($node);
        }

        return $nodeCollection;
    }

    abstract protected function createChildrenFactory(BoardInterface $copyOfBoard, $copyOfEmptyPositions): AbstractNodeFactory;

    abstract protected function createNode(Position $eachPosition, BoardInterface $copyOfBoard): AbstractNode;
}
