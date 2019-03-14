<?php
declare(strict_types=1);
namespace NAC\Tests\Domain;

use NAC\Domain\Board\Board;
use NAC\Domain\Board\Player;
use NAC\Domain\Field\Cross;
use NAC\Domain\Field\Naught;
use PHPUnit\Framework\TestCase;

class DrawTest extends TestCase
{
    /**
     * @dataProvider fieldsForDrawProvider
     */
    public function testDraw(array $fields)
    {
        $size = 3;
        $lineSize = 3;
        $board = new Board($size, $lineSize, Board::CROSS_PLAYER, '0123456789012');
        foreach ($fields as $eachField) {
            $board->putField($eachField);
        }

        $this->assertFalse($board->isVictory());
        $this->assertTrue($board->isDraw());
    }

    /**
     * @dataProvider fieldsForNotFinishedDrawProvider
     */
    public function testNotDraw(array $fields)
    {
        $size = 3;
        $lineSize = 3;
        $board = new Board($size, $lineSize, Board::CROSS_PLAYER, '0123456789012');
        foreach ($fields as $eachFields) {
            $board->putField($eachFields);
        }

        $this->assertFalse($board->isVictory());
        $this->assertFalse($board->isDraw());
    }

    public function fieldsForDrawProvider()
    {
        return [
            'every move for draw' => [
                [
                    new Cross(1, 1),
                    new Naught(2, 0),
                    new Cross(2, 2),
                    new Naught(0, 0),
                    new Cross(1, 0),
                    new Naught(1, 2),
                    new Cross(0, 1),
                    new Naught(2, 1),
                    new Cross(0, 2)
                ]
            ]
        ];
    }

    public function fieldsForNotFinishedDrawProvider()
    {
        return [
            'only one move to draw' => [
                [
                    new Cross(1, 1),
                    new Naught(2, 0),
                    new Cross(2, 2),
                    new Naught(0, 0),
                    new Cross(1, 0),
                    new Naught(1, 2),
                    new Cross(0, 1),
                    new Naught(2, 1)
                ]
            ],
            'fields with x coordinate bigger than zero' => [
                [
                    new Cross(1, 1),
                    new Naught(1, 2),
                    new Cross(2, 2),
                    new Naught(2, 1),
                    new Cross(1, 0),
                    new Naught(2, 0)
                ]
            ],
            'fields with y coordinate bigger than zero' => [
                [
                    new Cross(1, 1),
                    new Naught(1, 2),
                    new Cross(2, 2),
                    new Naught(2, 1),
                    new Cross(0, 1),
                    new Naught(0, 2)
                ]
            ]
        ];
    }
}