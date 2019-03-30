<?php
declare(strict_types=1);

namespace NAC\Tests\Domain\Game;

use NAC\Domain\Board\BoardInterface;
use NAC\Domain\Field\Position;
use NAC\Domain\Field\PositionCollection;
use NAC\Domain\Game\AINode;
use NAC\Domain\Game\AINodeFactory;
use NAC\Domain\Game\OpponentNode;
use PHPUnit\Framework\TestCase;

class NodeFactoryTest extends TestCase
{

    public function testCreatingTreeFromOnePosition()
    {
        $position = new Position(0, 0);
        $emptyPositions = new PositionCollection();
        $emptyPositions->attach($position);
        $board = $this->createMock(BoardInterface::class);
        $factory = new AINodeFactory($board, $emptyPositions);
        $children = $factory->create();
        $children->rewind();
        $node = $children->current();
        $this->assertEquals($position, $node->getPosition());
    }

    public function testStopBuildingTreeAfterTerminalNode()
    {
        $firstPosition = new Position(0, 1);
        $secondPosition = new Position(1, 0);
        $emptyPositions = new PositionCollection();
        $emptyPositions->attach($firstPosition);
        $emptyPositions->attach($secondPosition);

        $board = $this->createMock(BoardInterface::class);
        $board->method('isVictory')->willReturn(true);
        $factory = new AINodeFactory($board, $emptyPositions);
        $rootChildren = $factory->create();

        $rootChildren->rewind();
        $firstInternalNode = $rootChildren->current();
        $rootChildren->next();
        $secondInternalNode = $rootChildren->current();

        $firstInternalNodeChildren = $firstInternalNode->getChildren();
        $firstInternalNodeChildren->rewind();

        $secondInternalNodeChildren = $secondInternalNode->getChildren();
        $secondInternalNodeChildren->rewind();

        $this->assertEquals($firstPosition, $firstInternalNode->getPosition());
        $this->assertEquals($secondPosition, $secondInternalNode->getPosition());
        $this->assertFalse($firstInternalNodeChildren->valid());
        $this->assertFalse($secondInternalNodeChildren->valid());
        $this->assertContainsOnlyInstancesOf(
            AINode::class,
            [$firstInternalNode, $secondInternalNode]
        );
    }

    public function testCreatingTreeFromTwoPositions()
    {
        $firstPosition = new Position(0, 1);
        $secondPosition = new Position(1, 0);
        $emptyPositions = new PositionCollection();
        $emptyPositions->attach($firstPosition);
        $emptyPositions->attach($secondPosition);

        $board = $this->createMock(BoardInterface::class);
        $factory = new AINodeFactory($board, $emptyPositions);
        $rootChildren = $factory->create();

        $rootChildren->rewind();
        $firstInternalNode = $rootChildren->current();
        $rootChildren->next();
        $secondInternalNode = $rootChildren->current();

        $firstInternalNodeChildren = $firstInternalNode->getChildren();
        $firstInternalNodeChildren->rewind();
        $firstLeaf = $firstInternalNodeChildren->current();

        $secondInternalNodeChildren = $secondInternalNode->getChildren();
        $secondInternalNodeChildren->rewind();
        $secondLeaf = $secondInternalNodeChildren->current();

        $this->assertEquals($firstPosition, $firstInternalNode->getPosition());
        $this->assertEquals($secondPosition, $secondInternalNode->getPosition());
        $this->assertEquals($secondPosition, $firstLeaf->getPosition());
        $this->assertEquals($firstPosition, $secondLeaf->getPosition());
        $this->assertContainsOnlyInstancesOf(
            AINode::class,
            [$firstInternalNode, $secondInternalNode]
        );
        $this->assertContainsOnlyInstancesOf(
            OpponentNode::class,
            [$firstLeaf, $secondLeaf]
        );
    }



    public function testCreatingTreeFromThreePositions()
    {
        $firstPosition = new Position(0, 1);
        $secondPosition = new Position(1, 0);
        $thirdPosition = new Position(2, 0);
        $emptyPositions = new PositionCollection();
        $emptyPositions->attach($firstPosition);
        $emptyPositions->attach($secondPosition);
        $emptyPositions->attach($thirdPosition);

        $board = $this->createMock(BoardInterface::class);
        $factory = new AINodeFactory($board, $emptyPositions);
        $rootChildren = $factory->create();
        $rootChildren->rewind();
        $levelOneNodeFirst = $rootChildren->current();
        $rootChildren->next();
        $levelOneNodeSecond = $rootChildren->current();
        $rootChildren->next();
        $levelOneNodeThird = $rootChildren->current();

        $levelOneNodeFirstChildren = $levelOneNodeFirst->getChildren();
        $levelOneNodeFirstChildren->rewind();
        $levelTwoNodeFirst = $levelOneNodeFirstChildren->current();
        $levelOneNodeFirstChildren->next();
        $levelTwoNodeSecond = $levelOneNodeFirstChildren->current();

        $levelOneNodeSecondChildren = $levelOneNodeSecond->getChildren();
        $levelOneNodeSecondChildren->rewind();
        $levelTwoNodeThird = $levelOneNodeSecondChildren->current();
        $levelOneNodeSecondChildren->next();
        $levelTwoNodeFourth = $levelOneNodeSecondChildren->current();

        $levelOneNodeThirdChildren = $levelOneNodeThird->getChildren();
        $levelOneNodeThirdChildren->rewind();
        $levelTwoNodeFith = $levelOneNodeThirdChildren->current();
        $levelOneNodeThirdChildren->next();
        $levelTwoNodeSixth = $levelOneNodeThirdChildren->current();


        $levelTwoNodeFirstChildren = $levelTwoNodeFirst->getChildren();
        $levelTwoNodeFirstChildren->rewind();
        $levelThreeNodeFirst = $levelTwoNodeFirstChildren->current();

        $levelTwoNodeSecondChildren = $levelTwoNodeSecond->getChildren();
        $levelTwoNodeSecondChildren->rewind();
        $levelThreeNodeSecond = $levelTwoNodeSecondChildren->current();

        $levelTwoNodeThirdChildren = $levelTwoNodeThird->getChildren();
        $levelTwoNodeThirdChildren->rewind();
        $levelThreeNodeThird = $levelTwoNodeThirdChildren->current();


        $levelTwoNodeFourthChildren = $levelTwoNodeFourth->getChildren();
        $levelTwoNodeFourthChildren->rewind();
        $levelThreeNodeFourth = $levelTwoNodeFourthChildren->current();

        $levelTwoNodeFifthChildren = $levelTwoNodeFith->getChildren();
        $levelTwoNodeFifthChildren->rewind();
        $levelThreeNodeFifth = $levelTwoNodeFifthChildren->current();

        $levelTwoNodeSixthChildren = $levelTwoNodeSixth->getChildren();
        $levelTwoNodeSixthChildren->rewind();
        $levelThreeNodeSixth = $levelTwoNodeSixthChildren->current();

        $this->assertEquals($firstPosition, $levelOneNodeFirst->getPosition());
        $this->assertEquals($secondPosition, $levelOneNodeSecond->getPosition());
        $this->assertEquals($thirdPosition, $levelOneNodeThird->getPosition());
        $this->assertContainsOnlyInstancesOf(
            AINode::class,
            [$levelOneNodeFirst, $levelOneNodeSecond, $levelOneNodeThird]
        );

        $this->assertEquals($secondPosition, $levelTwoNodeFirst->getPosition());
        $this->assertEquals($thirdPosition, $levelTwoNodeSecond->getPosition());
        $this->assertEquals($firstPosition, $levelTwoNodeThird->getPosition());
        $this->assertEquals($thirdPosition, $levelTwoNodeFourth->getPosition());
        $this->assertEquals($firstPosition, $levelTwoNodeFith->getPosition());
        $this->assertEquals($secondPosition, $levelTwoNodeSixth->getPosition());
        $this->assertContainsOnlyInstancesOf(
            OpponentNode::class,
            [
                $levelTwoNodeFirst,
                $levelTwoNodeSecond,
                $levelTwoNodeThird,
                $levelTwoNodeFourth,
                $levelTwoNodeFith,
                $levelTwoNodeSixth
            ]
        );


        $this->assertEquals($thirdPosition, $levelThreeNodeFirst->getPosition());
        $this->assertEquals($secondPosition, $levelThreeNodeSecond->getPosition());
        $this->assertEquals($thirdPosition, $levelThreeNodeThird->getPosition());
        $this->assertEquals($firstPosition, $levelThreeNodeFourth->getPosition());
        $this->assertEquals($secondPosition, $levelThreeNodeFifth->getPosition());
        $this->assertEquals($firstPosition, $levelThreeNodeSixth->getPosition());
        $this->assertContainsOnlyInstancesOf(
            AINode::class,
            [
                $levelThreeNodeFirst,
                $levelThreeNodeSecond,
                $levelThreeNodeThird,
                $levelThreeNodeFourth,
                $levelThreeNodeFifth,
                $levelThreeNodeSixth
            ]
        );
    }
}