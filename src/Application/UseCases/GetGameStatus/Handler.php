<?php
declare(strict_types=1);
namespace NAC\Application\UseCases\GetGameStatus;

use NAC\Application\Persistence\Board\RepositoryInterface;

class Handler
{
    private $boardRepository;

    public function __construct(RepositoryInterface $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    public function handle(Query $query): GameStatus
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