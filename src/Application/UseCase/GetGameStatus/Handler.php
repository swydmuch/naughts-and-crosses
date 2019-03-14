<?php
declare(strict_types=1);
namespace NAC\Application\UseCase\GetGameStatus;

use NAC\Application\BoardRepository;

class Handler
{
    private $boardRepository;

    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    public function handle(Query $query)
    {
        $board = $this->boardRepository->getById($query->getBoardId());
        if ($board->isVictory()) {
            $status = GameStatus::STATUS_VICTORY;
        } else if ($board->isDraw()) {
            $status = GameStatus::STATUS_DRAW;
        } else {
            $status = GameStatus::STATUS_CONTINUES;
        }

        return new GameStatus($status);
    }
}