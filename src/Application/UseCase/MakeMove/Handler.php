<?php
declare(strict_types=1);

namespace NAC\Application\UseCase\MakeMove;

use NAC\Application\BoardRepository;
use NAC\Domain\Field\Position;

class Handler
{
    private $boardRepository;

    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    public function handle(Command $command)
    {
        $board = $this->boardRepository->getById($command->getBoardId());
        $board->take(new Position($command->getCoordinateX(), $command->getCoordinateY()));
    }
}