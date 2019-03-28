<?php
declare(strict_types=1);

namespace NAC\Application\UseCases\MakeMove;

use NAC\Application\BoardRepository;
use NAC\Application\Persistence\Board\EntityManagerInterface;
use NAC\Application\Persistence\Board\RepositoryInterface;
use NAC\Domain\Field\Position;

class Handler
{
    private $boardRepository;
    private $entityManager;

    public function __construct(RepositoryInterface $boardRepository, EntityManagerInterface $entityManager)
    {
        $this->boardRepository = $boardRepository;
        $this->entityManager = $entityManager;
    }

    public function handle(Command $command)
    {
        $board = $this->boardRepository->getById($command->getBoardId());
        $board->take(new Position($command->getCoordinateX(), $command->getCoordinateY()));
        $this->entityManager->flush();
    }
}