<?php
declare(strict_types=1);
namespace NAC\Application\UseCase\CreateGame;

class Command
{
    private $boardSize;
    private $lineSize;
    private $startingPlayer;
    private $boardId;

    public function __construct(int $boardSize, int $lineSize, int $startingPlayer, string $boardId)
    {
        $this->boardSize = $boardSize;
        $this->lineSize = $lineSize;
        $this->startingPlayer = $startingPlayer;
        $this->boardId = $boardId;
    }

    /**
     * @return int
     */
    public function getBoardSize(): int
    {
        return $this->boardSize;
    }

    /**
     * @return int
     */
    public function getLineSize(): int
    {
        return $this->lineSize;
    }

    /**
     * @return int
     */
    public function getStartingPlayer(): int
    {
        return $this->startingPlayer;
    }

    /**
     * @return string
     */
    public function getBoardId(): string
    {
        return $this->boardId;
    }



}