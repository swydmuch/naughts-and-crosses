<?php
declare(strict_types=1);
namespace tests\Domain\Game;

use NAC\Domain\Board\BoardInterface;
use NAC\Domain\Field\Position;
use PHPUnit\Framework\TestCase;
use NAC\Domain\Game\AINode;
use NAC\Domain\Game\OpponentNode;


class TerminalNodeTest extends TestCase
{
    private $position;
    private $board;
    
    public function setUp()
    {
        $this->position = new Position(0, 0);
        $this->board = $this->createMock(BoardInterface::class);
    }

    public function testTakingPositionOnBoard()
    {
        $this->board->expects($this->once())
            ->method('take')
            ->with($this->position);
        new AINode($this->position, $this->board);
    }

    public function testWinOnTerminalAINode()
    {
        $this->board->method('isVictory')->willReturn(true);
        $this->board->method('isDraw')->willReturn(false);
        $node = new AINode($this->position, $this->board);
        $expectedValue = AINode::VALUE_FOR_VICTORY;
        $this->assertSame($expectedValue, $node->getValue());
        $this->assertTrue($node->isTerminal());
    }
    
    public function testDrawOnTerminalAINode()
    {
        $this->board->method('isVictory')->willReturn(false);
        $this->board->method('isDraw')->willReturn(true);
        $node = new AINode($this->position, $this->board);
        $expectedValue = AINode::VALUE_FOR_DRAW;
        $this->assertSame($expectedValue, $node->getValue());
        $this->assertTrue($node->isTerminal());
    }
    
    public function testWinOnTerminalOpponentNode()
    {
        $this->board->method('isVictory')->willReturn(true);
        $this->board->method('isDraw')->willReturn(false);
        $node = new OpponentNode($this->position, $this->board);
        $expectedValue = OpponentNode::VALUE_FOR_VICTORY;
        $this->assertSame($expectedValue, $node->getValue());
        $this->assertTrue($node->isTerminal());
    }
    
    public function testDrawOnTerminalOpponentNode()
    {
        $this->board->method('isVictory')->willReturn(false);
        $this->board->method('isDraw')->willReturn(true);
        $node = new OpponentNode($this->position, $this->board);
        $expectedValue = OpponentNode::VALUE_FOR_DRAW;
        $this->assertSame($expectedValue, $node->getValue());
        $this->assertTrue($node->isTerminal());
    }
}


