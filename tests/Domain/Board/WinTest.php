<?php
declare(strict_types=1);
namespace NAC\Tests\Domain;

use NAC\Domain\Board\AlreadyFinishedGameException;
use NAC\Domain\Board\BoardInterface;
use NAC\Domain\Field\Cross;
use NAC\Domain\Field\Naught;
use PHPUnit\Framework\TestCase;
use NAC\Domain\Board\Board;

class WinTest extends TestCase
{
    /** @var BoardInterface */
    private $board;

    protected function setUp()
    {
        parent::setUp();
        $boardSize = 3;
        $lineSize = $boardSize;
        $this->board = new Board($boardSize, $lineSize);
    }

    public function testNotWinningOnEmptyBoard()
    {
        $this->assertFalse($this->board->isVictory());
        $this->assertFalse($this->board->isDraw());
    }

    /**
     * @dataProvider threeFieldsInLineProvider
     */
    public function testGettingWinningLineWithThreeFields(array $fields)
    {
        foreach ($fields as $eachField) {
            $this->board->putField($eachField);
        }

        $expectedWinningFields = array_reverse($fields);

        $this->assertTrue($this->board->isVictory());
        $this->assertFalse($this->board->isDraw());
        $this->assertSame($expectedWinningFields, $this->board->getWinningFields());
    }

    public function threeFieldsInLineProvider()
    {
        return [

            'vertical line up' => [
                [
                    new Naught(0, 2),
                    new Naught(0, 1),
                    new Naught(0, 0)
                ]
            ],
            'vertical line down' => [
                [
                    new Naught(0, 0),
                    new Naught(0, 1),
                    new Naught(0, 2)
                ]
            ],
            'horizontal line right' => [
                [
                    new Naught(2, 0),
                    new Naught(1, 0),
                    new Naught(0, 0)
                ]
            ],
            'horizontal line left' => [
                [
                    new Naught(0, 0),
                    new Naught(1, 0),
                    new Naught(2, 0)
                ]
            ],
            'diagonal left bottom' => [
                [
                    new Naught(0, 0),
                    new Naught(1, 1),
                    new Naught(2, 2)
                ]
            ],
            'diagonal right top' => [
                [
                    new Naught(2, 2),
                    new Naught(1, 1),
                    new Naught(0, 0)
                ]
            ],
            'diagonal left top' => [
                [
                    new Naught(0, 2),
                    new Naught(1, 1),
                    new Naught(2, 0)
                ]
            ],
            'diagonal right bottom' => [
                [
                    new Naught(2, 0),
                    new Naught(1, 1),
                    new Naught(0, 2)
                ]
            ]
        ];
    }

    /**
     * @dataProvider twoFieldsInLineProvider
     */
    public function testNotWinningWithOnlyTwoFields(array $fields)
    {
        foreach ($fields as $eachFields) {
            $this->board->putField($eachFields);
        }

        $this->assertFalse($this->board->isVictory());
        $this->assertFalse($this->board->isDraw());
    }

    public function twoFieldsInLineProvider()
    {
        return [
            'vertical line up' => [
                [
                    new Naught(0, 2),
                    new Naught(0, 1)
                ]
            ],
            'vertical line down' => [
                [
                    new Naught(0, 0),
                    new Naught(0, 1)
                ]
            ],
            'horizontal line right' => [
                [
                    new Naught(2, 0),
                    new Naught(1, 0)
                ]
            ],
            'horizontal line left' => [
                [
                    new Naught(0, 0),
                    new Naught(1, 0)
                ]
            ],
            'diagonal left bottom' => [
                [
                    new Naught(0, 0),
                    new Naught(1, 1)
                ]
            ],
            'diagonal right top' => [
                [
                    new Naught(2, 2),
                    new Naught(1, 1)
                ]
            ],
            'diagonal left top' => [
                [
                    new Naught(0, 2),
                    new Naught(1, 1)
                ]
            ],
            'diagonal right bottom' => [
                [
                    new Naught(2, 0),
                    new Naught(1, 1)
                ]
            ]
        ];
    }

    /**
     * @dataProvider twoNaughtAndCrossInLineProvider
     */
    public function testNotWinningWithTwoFieldsNaughtAndOneCross(array $fields)
    {
        foreach ($fields as $eachField) {
            $this->board->putField($eachField);
        }

        $this->assertFalse($this->board->isVictory());
        $this->assertFalse($this->board->isDraw());
    }

    public function twoNaughtAndCrossInLineProvider()
    {
        return [
            'vertical line up' => [
                [
                    new Naught(0, 2),
                    new Naught(0, 1),
                    new Cross(0, 0)
                ]
            ],
            'vertical line down' => [
                [
                    new Naught(0, 0),
                    new Naught(0, 1),
                    new Cross(0, 2)
                ]
            ],
            'horizontal line right' => [
                [
                    new Naught(2, 0),
                    new Naught(1, 0),
                    new Cross(0, 0)
                ]
            ],
            'horizontal line left' => [
                [
                    new Naught(0, 0),
                    new Naught(1, 0),
                    new Cross(2, 0)
                ]
            ],
            'diagonal left bottom' => [
                [
                    new Naught(0, 0),
                    new Naught(1, 1),
                    new Cross(2, 2)
                ]
            ],
            'diagonal right top' => [
                [
                    new Naught(2, 2),
                    new Naught(1, 1),
                    new Cross(0, 0)
                ]
            ],
            'diagonal left top' => [
                [
                    new Naught(0, 2),
                    new Naught(1, 1),
                    new Cross(2, 0)
                ]
            ],
            'diagonal right bottom' => [
                [
                    new Naught(2, 0),
                    new Naught(1, 1),
                    new Cross(0, 2)
                ]
            ]
        ];
    }


    public function testMakingMoveAfterWin()
    {
        $fields = [
            new Naught(0, 2),
            new Naught(0, 1),
            new Naught(0, 0)
        ];

        foreach ($fields as $eachField) {
            $this->board->putField($eachField);
        }

        $this->expectException(AlreadyFinishedGameException::class);

        $faultFieldX = 1;
        $faultFieldY = 0;
        $faultField = new Naught($faultFieldX, $faultFieldY);
        $this->board->putField($faultField);
    }


}
