<?php
declare(strict_types=1);

namespace NAC\Application\UseCase\MakeMove;

class Command
{
    private $coordinateX;
    private $coordinateY;
    private $boardId;

    public function __construct(int $coordinateX, int $coordinateY, string  $boardId)
    {
        $this->coordinateX = $coordinateX;
        $this->coordinateY = $coordinateY;
        $this->boardId = $boardId;
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getCoordinateX(): int
    {
        return $this->coordinateX;
    }

    public function getCoordinateY(): int
    {
        return $this->coordinateY;
    }
}