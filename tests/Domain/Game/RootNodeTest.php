<?php
declare(strict_types=1);
namespace NAC\Tests\Domain\Game;

use NAC\Domain\Game\AINode;
use NAC\Domain\Game\NodeCollection;
use NAC\Domain\Game\AbstractNodeFactory;
use NAC\Domain\Game\RootNode;
use PHPUnit\Framework\TestCase;

class RootNodeTest extends TestCase
{
    public function testGettingBestPropose()
    {
        $childWithDraw = $this->createMock(AINode::class);
        $childWithDraw->method('getValue')->willReturn(AINode::VALUE_FOR_DRAW);
        $childWithVictory = $this->createMock(AINode::class);
        $childWithVictory->method('getValue')->willReturn(AINode::VALUE_FOR_VICTORY);
        $children = new NodeCollection();
        $children->attach($childWithDraw);
        $children->attach($childWithVictory);
        $factory = $this->createMock(AbstractNodeFactory::class);
        $factory->method('create')->willReturn($children);
        $root = new RootNode($factory);
        $bestChild = $root->getBestChild();
        $this->assertSame($childWithVictory, $bestChild);
    }
}
