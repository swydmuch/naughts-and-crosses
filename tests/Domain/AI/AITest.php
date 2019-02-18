<?php
declare(strict_types=1);
namespace NAC\Tests\Domain\AI;

use NAC\Domain\AI\AI;
use NAC\Domain\Board\AlreadyFinishedGameException;
use NAC\Domain\Board\BoardInterface;
use NAC\Domain\Field\Cross;
use NAC\Domain\Field\Naught;
use PHPUnit\Framework\TestCase;

class AITest extends TestCase
{
    public function testEvaluationAfterFinishedGame()
    {
        $this->expectException(AlreadyFinishedGameException::class);
        $board = $this->createMock(BoardInterface::class);
        $board->method('getNotTakenPositions')->willReturn([]);
        $ai = new AI($board);
        $ai->evaluateNextPosition();
    }

    public function testPuttingCrossFieldDuringEvaluation()
    {
        $emptyPositions = [
            [0, 1]
        ];
        $expectedField = new Cross(0, 1);
        $board = $this->createMock(BoardInterface::class);
        $board->method('getNotTakenPositions')->willReturn($emptyPositions);
        $board->method('isVictory')->willReturn(true);
        $board->method('isCrossMove')->willReturn(true);
        $board->expects($this->once())
            ->method('putField')
            ->with($expectedField);
        $ai = new AI($board);
        $ai->evaluateNextPosition();
    }

    public function testPuttingNaughtFieldDuringEvaluation()
    {
        $emptyPositions = [
            [0, 1]
        ];
        $expectedField = new Naught(0, 1);
        $board = $this->createMock(BoardInterface::class);
        $board->method('getNotTakenPositions')->willReturn($emptyPositions);
        $board->method('isVictory')->willReturn(true);
        $board->method('isCrossMove')->willReturn(false);
        $board->expects($this->once())
            ->method('putField')
            ->with($expectedField);
        $ai = new AI($board);
        $ai->evaluateNextPosition();
    }

    /**
     * @dataProvider provideDateForEvaluation
     */
    public function testEvaluationBestPropositionForNextMove(
        array $emptyPositions,
        array $expectedProposedPosition,
        array $statesOfVictory,
        array $statesOfDraw
    ): void {
        $board = $this->createMock(BoardInterface::class);
        $board->method('getNotTakenPositions')->willReturn($emptyPositions);
        $board->method('isVictory')->willReturnOnConsecutiveCalls(...$statesOfVictory);
        $board->method('isDraw')->willReturnOnConsecutiveCalls(...$statesOfDraw);

        $ai = new AI($board);

        $this->assertSame($expectedProposedPosition, $ai->evaluateNextPosition());
    }

    public function provideDateForEvaluation(): array
    {
        return [
            'TwoMovesToDrawFromTwoFields' => $this->getDataForTwoMovesToDrawFromTwoFields(),
            'OneMoveToVictoryFromOneFields' => $this->getDataForOneMoveToVictoryFromOneFields(),
            'OneMovesToVictoryFromTwoFields' => $this->getDataForOneMovesToVictoryFromTwoFields(),
            'OneMoveToVictoryInTwoVariantsFromTwoFields' => $this->getDataForOneMoveToVictoryInTwoVariantsFromTwoFields(),
            'TwoMovesToVictoryFromThreeFields' => $this->getDataForTwoMovesToVictoryFromThreeFields(),
            'TwoMovesToVictoryInTwoVariantsFromThreeFields' => $this->getDataForTwoMovesToVictoryInTwoVariantsFromThreeFields()

        ];
    }

    private function getDataForTwoMovesToDrawFromTwoFields(): array
    {
        $emptyPositions = [
            [0, 1],
            [1, 2]
        ];
        $expectedProposedPosition = [1, 2];
        $statesOfVictoryWhenMovingTreeGame = [
            false, true, //subtree for first position
            false, false //subtree for second position
        ];
        $statesOfDrawWhenMovingTreeGame = [
            false, false, //subtree for first position
            false, true //subtree for second position
        ];

        return [
            $emptyPositions,
            $expectedProposedPosition,
            $statesOfVictoryWhenMovingTreeGame,
            $statesOfDrawWhenMovingTreeGame
        ];
    }

    private function getDataForOneMoveToVictoryFromOneFields(): array
    {
        $emptyPositions = [
            [0, 1]
        ];
        $expectedProposedPosition = [0, 1];
        $statesOfVictoryWhenMovingTreeGame = [true];
        $statesOfDrawWhenMovingTreeGame = [false];

        return [
            $emptyPositions,
            $expectedProposedPosition,
            $statesOfVictoryWhenMovingTreeGame,
            $statesOfDrawWhenMovingTreeGame
        ];
    }

    private function getDataForOneMovesToVictoryFromTwoFields(): array
    {
        $emptyPositions = [
            [0, 1],
            [1, 2]
        ];
        $expectedProposedPosition = [0, 1];
        $statesOfVictoryWhenMovingTreeGame = [true, false, true];
        $statesOfDrawWhenMovingTreeGame = [false, false, false];

        return [
            $emptyPositions,
            $expectedProposedPosition,
            $statesOfVictoryWhenMovingTreeGame,
            $statesOfDrawWhenMovingTreeGame
        ];
    }

    private function getDataForOneMoveToVictoryInTwoVariantsFromTwoFields(): array
    {
        $emptyPositions = [
            [0, 1],
            [1, 2]
        ];
        $expectedProposedPosition = [0, 1];
        $statesOfVictoryWhenMovingTreeGame = [true, true];
        $statesOfDrawWhenMovingTreeGame = [false, false];

        return [
            $emptyPositions,
            $expectedProposedPosition,
            $statesOfVictoryWhenMovingTreeGame,
            $statesOfDrawWhenMovingTreeGame
        ];
    }

    private function getDataForTwoMovesToVictoryFromThreeFields(): array
    {
        $emptyPositions = [
            [0, 1],
            [1, 2],
            [2, 0]
        ];
        $expectedProposedPosition = [1, 2];
        $statesOfVictoryWhenMovingTreeGame = [
            false, true, false, true, //subtree for first position
            false, false, true, false, true, //subtree for second position
            false, true, true //subtree for third position
        ];
        $statesOfDrawWhenMovingTreeGame = array_fill(0, 12, false);

        return [
            $emptyPositions,
            $expectedProposedPosition,
            $statesOfVictoryWhenMovingTreeGame,
            $statesOfDrawWhenMovingTreeGame
        ];
    }

    private function getDataForTwoMovesToVictoryInTwoVariantsFromThreeFields(): array
    {
        $emptyPositions = [
            [0, 1],
            [1, 2],
            [2, 0]
        ];
        $expectedProposedPosition = [0, 1];
        $statesOfVictoryWhenMovingTreeGame = [
            false, false, true, false, true, //subtree for first position
            false, true, true, //subtree for second position
            false, true, true //subtree for third position
        ];
        $statesOfDrawWhenMovingTreeGame = array_fill(0, 11, false);

        return [
            $emptyPositions,
            $expectedProposedPosition,
            $statesOfVictoryWhenMovingTreeGame,
            $statesOfDrawWhenMovingTreeGame
        ];
    }
    //TODO launch mutation test
    //TODO refactor AI class
}
