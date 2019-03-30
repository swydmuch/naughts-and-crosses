<?php
declare(strict_types=1);
namespace tests\Domain\Game;

use NAC\Domain\Field\Position;
use NAC\Domain\Game\NodeCollection;
use NAC\Domain\Game\NotEvaluatedValueForNodeException;
use PHPUnit\Framework\TestCase;
use NAC\Domain\Game\AINode;
use NAC\Domain\Board\Board;
use NAC\Domain\Game\OpponentNode;

class InternalNodeTest extends TestCase
{
    public function testValueForInternalNodeBeforeEvaluating()
    {
        $this->expectException(NotEvaluatedValueForNodeException::class);
        $position = new Position(0, 0);
        $board = $this->createMock(Board::class);
        $internalNode = new AINode($position, $board);
        $internalNode->getValue();
    }



    public function testAddingNode()
    {
        $upperPosition = new Position(0, 0);
        $leafPosition = new Position(0, 1);
        $board = $this->createMock(Board::class);
        $upperNode = new AINode($upperPosition, $board);
        $leafNode = new OpponentNode($leafPosition, $board);
        $upperNode->addChild($leafNode);
        $expectedNodes = new NodeCollection();
        $expectedNodes->attach($leafNode);
        $this->assertEquals($expectedNodes, $upperNode->getChildren());
        $this->assertFalse($upperNode->isTerminal());
    }
    
    public function testEvaluatingBestOpponentMove()
    {
        $position = new Position(0, 0);
        $board = $this->createMock(Board::class);
        $AINode = new AINode($position, $board);
        $nodeWithDraw = $this->createMock(OpponentNode::class);
        $nodeWithDraw->method('getValue')->willReturn(OpponentNode::VALUE_FOR_DRAW);
        $nodeWithOpponentVictory = $this->createMock(OpponentNode::class);
        $nodeWithOpponentVictory->method('getValue')->willReturn(OpponentNode::VALUE_FOR_VICTORY);
        $AINode->addChild($nodeWithDraw);
        $AINode->addChild($nodeWithOpponentVictory);

        $AINode->evaluateBestValue();

        $this->assertSame(OpponentNode::VALUE_FOR_VICTORY, $AINode->getValue());
    }

    public function testEvaluatingBestAIMove()
    {
        $position = new Position(0, 0);
        $board = $this->createMock(Board::class);
        $opponentNode = new OpponentNode($position, $board);
        $nodeWithDraw = $this->createMock(AINode::class);
        $nodeWithDraw->method('getValue')->willReturn(AINode::VALUE_FOR_DRAW);
        $nodeWithAIVictory = $this->createMock(AINode::class);
        $nodeWithAIVictory->method('getValue')->willReturn(AINode::VALUE_FOR_VICTORY);
        $opponentNode->addChild($nodeWithDraw);
        $opponentNode->addChild($nodeWithAIVictory);

        $opponentNode->evaluateBestValue();

        $this->assertSame(AINode::VALUE_FOR_VICTORY, $opponentNode->getValue());
    }

    public function testEvaluatingBestMoveForOpponentFromTwoLevels()
    {
        $position = new Position(0, 0);
        $board = $this->createMock(Board::class);
        $AINode = new AINode($position, $board);
        $internalNodeWithDraw = $this->createMock(OpponentNode::class);
        $internalNodeWithDraw->expects($this->once())->method('evaluateBestValue');
        $internalNodeWithDraw->method('getValue')->willReturn(OpponentNode::VALUE_FOR_DRAW);
        $internalNodeWithVictory = $this->createMock(OpponentNode::class);
        $internalNodeWithVictory->expects($this->once())->method('evaluateBestValue');
        $internalNodeWithVictory->method('getValue')->willReturn(OpponentNode::VALUE_FOR_VICTORY);
        $AINode->addChild($internalNodeWithDraw);
        $AINode->addChild($internalNodeWithVictory);

        $AINode->evaluateBestValue();

        $this->assertSame(OpponentNode::VALUE_FOR_VICTORY, $AINode->getValue());
    }

    public function testEvaluatingBestMoveForAIFromTwoLevels()
    {
        $position = new Position(0, 0);
        $board = $this->createMock(Board::class);
        $OpponentNode = new OpponentNode($position, $board);
        $internalNodeWithDraw = $this->createMock(AINode::class);
        $internalNodeWithDraw->expects($this->once())->method('evaluateBestValue');
        $internalNodeWithDraw->method('getValue')->willReturn(AINode::VALUE_FOR_DRAW);
        $internalNodeWithVictory = $this->createMock(AINode::class);
        $internalNodeWithVictory->expects($this->once())->method('evaluateBestValue');
        $internalNodeWithVictory->method('getValue')->willReturn(AINode::VALUE_FOR_VICTORY);
        $secondInternalNodeWithVictory = $this->createMock(AINode::class);
        $secondInternalNodeWithVictory->expects($this->once())->method('evaluateBestValue');
        $secondInternalNodeWithVictory->method('getValue')->willReturn(AINode::VALUE_FOR_VICTORY);
        $OpponentNode->addChild($internalNodeWithDraw);
        $OpponentNode->addChild($internalNodeWithVictory);
        $OpponentNode->addChild($secondInternalNodeWithVictory);

        $OpponentNode->evaluateBestValue();

        $this->assertSame(AINode::VALUE_FOR_VICTORY, $OpponentNode->getValue());
    }
    
}


