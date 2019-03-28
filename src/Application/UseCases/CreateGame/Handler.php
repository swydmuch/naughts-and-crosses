<?php
declare(strict_types=1);

namespace NAC\Application\UseCases\CreateGame;

use NAC\Application\Persistence\Board\EntityManagerInterface;
use NAC\Domain\Board\Board;

class Handler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(Command $command)
    {
        $board = new Board(
            $command->getBoardSize(),
            $command->getLineSize(),
            $command->getStartingPlayer(),
            $command->getBoardId()
        );
        $this->entityManager->persist($board);
        $this->entityManager->flush();
    }
}