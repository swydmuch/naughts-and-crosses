<?php
declare(strict_types=1);

namespace NAC\Tests\Domain\Game;

use NAC\Domain\Board\Board;
use NAC\Domain\Field\Position;
use NAC\Domain\Game\AINodeFactory;
use NAC\Domain\Game\RootNode;
use NAC\Infrastructure\IdGenerator;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testGameWhenAIMustPreventLosing()
    {
        $boardId = IdGenerator::generate();
        $board = new Board(3, 3, 1, $boardId);
        $board->take(new Position(0,0)); //x
        $board->take(new Position(1,1)); //o
        $board->take(new Position(2,0)); //x
        $board->take(new Position(0,2)); //o
        $board->take(new Position(0,1)); //x
        $emptyPositions = $board->getEmptyPositions();
        $factory = new AINodeFactory($board, $emptyPositions);
        $root = new RootNode($factory);
        $bestMove = $root->getBestChild();
        foreach ($root->getChildren() as $child) {
            $temp = $child;
        }

        $this->assertEquals(new Position(1,0), $bestMove->getPosition());
    }

    public function testGameWhenHumanWins()
    {
        $boardId = IdGenerator::generate();
        $board = new Board(3, 3, 1, $boardId);
        $board->take(new Position(0,0)); //x
        $board->take(new Position(1,1)); //o
        $board->take(new Position(2,0)); //x
        $board->take(new Position(0,2)); //o
        $board->take(new Position(0,1)); //x
        $board->take(new Position(1,2)); //o
        $board->take(new Position(1,0)); //x
        $this->assertTrue($board->isVictory());
    }

}