<?php
namespace NAC\Domain\Game;

use NAC\Domain\Field\FieldInterface;
use NAC\Domain\Board\BoardInterface;
use NAC\Domain\Field\Position;
use PhpParser\Node;

abstract class AbstractNode
{
    protected $children;
    private $position;
    private $value;
    private $isTerminal;
    const VALUE_FOR_DRAW = 0;
    
    public function __construct(Position $position, BoardInterface $copyOfBoard)
    {
        $this->children = new NodeCollection();
        $this->position = $position;
        $copyOfBoard->take($this->position);
        $isVictory = $copyOfBoard->isVictory();
        $isDraw = $copyOfBoard->isDraw();
        if ($isVictory) {
            $this->value = static::VALUE_FOR_VICTORY;
            $this->isTerminal = true;
        } elseif ($isDraw) {
            $this->value = self::VALUE_FOR_DRAW;
            $this->isTerminal = true;
        } else {
            $this->value = null;
            $this->isTerminal = false;
        }
    }
    
    public function getValue(): int
    {
        if (is_null($this->value)) {
            throw new NotEvaluatedValueForNodeException();
        }

        return $this->value;
    }
    
    public function addChild(AbstractNode $node): void
    {
        $this->children->attach($node);
    }
    
    public function getChildren(): NodeCollection
    {
        return $this->children;
    }

    protected function getMaxChild(): AbstractNode
    {
        $children = $this->getChildren();
        $maxChild = null;
        $max = null;

        foreach ($children as $child) {
            $child->evaluateBestValue();
            $valueOfChild = $child->getValue();
            if (is_null($max) || $valueOfChild > $max) {
                $max = $valueOfChild;
                $maxChild = $child;
            }
        }

        return $maxChild;
    }

    protected function getMinChild(): AbstractNode
    {
        $min = null;
        $minChild = null;
        $children = $this->getChildren();
        foreach ($children as $child) {
            $child->evaluateBestValue();
            $valueOfChild = $child->getValue();
            if (is_null($min) || $valueOfChild < $min) {
                $min = $valueOfChild;
                $minChild = $child;
            }
        }

        return $minChild;
    }

    public function getPosition(): Position
    {
        return new Position($this->position->getCoordinateX(), $this->position->getCoordinateY());
    }

    public function evaluateBestValue(): void
    {
        if (is_null($this->value)) {
            $bestChild = $this->getBestChild();
            $this->value = $bestChild->getValue();
        }
    }

    public function isTerminal(): bool
    {
        return $this->isTerminal;
    }
        
    abstract protected function getBestChild(): AbstractNode;
}
