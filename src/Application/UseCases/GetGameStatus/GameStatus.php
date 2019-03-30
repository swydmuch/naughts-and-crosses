<?php
declare(strict_types=1);
namespace NAC\Application\UseCases\GetGameStatus;

class GameStatus
{
    const STATUS_VICTORY = 3;
    const STATUS_DRAW = 2;
    const STATUS_CONTINUES = 1;
    const NAMES = [
        self::STATUS_VICTORY => 'victory',
        self::STATUS_DRAW => 'draw',
        self::STATUS_CONTINUES => 'continues'
    ];

    private $status;

    public function __construct(int $status)
    {
        if (!in_array($status, [self::STATUS_CONTINUES, self::STATUS_DRAW, self::STATUS_VICTORY])) {
            throw new WrongStatusException();
        }

        $this->status = $status;
    }

    public function getNameOfStatus(): string
    {
        return self::NAMES[$this->status];
    }

}