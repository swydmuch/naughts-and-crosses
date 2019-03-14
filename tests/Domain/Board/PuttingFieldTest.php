<?php
declare(strict_types=1);
namespace NAC\Tests\Domain;

use NAC\Domain\Board\WrongPlayerException;
use NAC\Domain\Field\Cross;
use NAC\Domain\Field\InvalidCoordinateException;
use NAC\Domain\Board\AlreadyTakenPositionException;
use NAC\Domain\Board\LineSizeBiggerThanBoardException;
use NAC\Domain\Board\TooSmallBoardSizeException;
use NAC\Domain\Field\Position;
use PHPUnit\Framework\TestCase;
use NAC\Domain\Board\Board;

class PuttingFieldTest extends TestCase
{
    /**
     * @dataProvider notValidCoordinateProvider
     */
    public function testPuttingFieldWithNotValidCoordinate(int $coordinateX, int $coordinateY, int $boardSize): void
    {
        $this->expectException(InvalidCoordinateException::class);

        $position = new Position($coordinateX, $coordinateY);
        $board = new Board($boardSize, $boardSize, Board::CROSS_PLAYER, '0123456789012');

        $board->take($position);
    }

    /**
     * @dataProvider notValidCoordinateProvider
     */
    public function testCheckingToHighFieldCoordinate(int $coordinateX, int $coordinateY, int $boardSize): void
    {
        $this->expectException(InvalidCoordinateException::class);

        $board = new Board($boardSize, $boardSize, Board::CROSS_PLAYER, '0123456789012');

        $board->isTakenPosition($coordinateX, $coordinateY);
    }

    /**
     * @dataProvider notValidCoordinateProvider
     */
    public function testGettingToHighFieldCoordinate(int $coordinateX, int $coordinateY, int $boardSize): void
    {
        $this->expectException(InvalidCoordinateException::class);

        $board = new Board($boardSize, $boardSize, Board::CROSS_PLAYER, '0123456789012');

        $board->getFieldByXY($coordinateX, $coordinateY);
    }

    /**
     * Provide coordinate greater than board size
     * @return array
     */
    public function notValidCoordinateProvider(): array
    {
        return [
            [-1, 2, 3],
            [2, -1, 3],
            [0, 3, 3],
            [3, 0, 3]
        ];
    }

    /**
     * @dataProvider validCoordinateProvider
     */
    public function testProperFieldCoordinate(int $coordinateX, int $coordinateY, int $boardSize): void
    {
        $position = new Position($coordinateX, $coordinateY);
        $board = new Board($boardSize, $boardSize, Board::CROSS_PLAYER, '0123456789012');

        $board->take($position);

        $this->assertTrue($board->isTakenPosition($coordinateX, $coordinateY));
    }

    /**
     * Provide coordinate located in board size
     * @return array
     */
    public function validCoordinateProvider(): array
    {
        return [
            [0, 2, 3],
            [2, 0, 3]
        ];
    }

    public function testAlreadyPuttedField(): void
    {
        $this->expectException(AlreadyTakenPositionException::class);

        $field = new Cross(0, 0);
        $board = new Board(3, 3, Board::CROSS_PLAYER, '0123456789012');
        $board->putField($field);

        $board->putField($field);
    }

    public function testTooSmallBoardSize(): void
    {
        $this->expectException(TooSmallBoardSizeException::class);
        new Board(2, 2, Board::CROSS_PLAYER, '0123456789012');
    }

    public function testLineSizeBiggerThanBoardSize(): void
    {
        $this->expectException(LineSizeBiggerThanBoardException::class);
        new Board(3, 4, Board::CROSS_PLAYER, '0123456789012');
    }

    public function testCheckingNextPlayerCross(): void
    {
        $board = new Board(3, 3, Board::CROSS_PLAYER, '0123456789012');
        $this->assertTrue($board->isCrossMove());
    }

    public function testCheckingNextPlayerNaught(): void
    {
        $board = new Board(3, 3, Board::NAUGHT_PLAYER, '0123456789012');
        $this->assertTrue($board->isNaughtMove());
    }

    public function testSettingBoardWithWrongPlayer(): void
    {
        $this->expectException(WrongPlayerException::class);
        $wrongPlayer = 3;
        new Board(3, 3, $wrongPlayer, '0123456789012');
    }
}
