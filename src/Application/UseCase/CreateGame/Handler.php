<?php
declare(strict_types=1);

namespace NAC\Application\UseCase\CreateGame;

use NAC\Application\PersistenceManagerInterface;
use NAC\Domain\Board\Board;

class Handler
{
    private $persistenceManager;

    public function __construct(PersistenceManagerInterface $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    public function handle(Command $command)
    {
        $board = new Board(
            $command->getBoardSize(),
            $command->getLineSize(),
            $command->getStartingPlayer(),
            $command->getBoardId()
        );
        $this->persistenceManager->persist($board);
    }
}