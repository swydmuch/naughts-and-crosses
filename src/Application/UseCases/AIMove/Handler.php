<?php
declare(strict_types=1);

namespace NAC\Application\UseCases\AIMove;

use NAC\Application\BoardRepository;
use NAC\Application\Persistence\Board\EntityManagerInterface;
use NAC\Application\Persistence\Board\RepositoryInterface;
use NAC\Domain\Field\Position;
use NAC\Domain\Game\AINodeFactory;
use NAC\Domain\Game\RootNode;

class Handler
{
    private $boardRepository;
    private $entityManager;
    private $responseData;

    public function __construct(RepositoryInterface $boardRepository, EntityManagerInterface $entityManager, \stdClass $responseData)
    {
        $this->boardRepository = $boardRepository;
        $this->entityManager = $entityManager;
        $this->responseData = $responseData;
    }

    public function handle(Command $command)
    {
        $board = $this->boardRepository->getById($command->getBoardId());
        $emptyPositions = $board->getEmptyPositions();
        $factory = new AINodeFactory($board, $emptyPositions);
        $root = new RootNode($factory);
        $bestChild = $root->getBestChild();
        $coordinateX = $bestChild->getPosition()->getCoordinateX();
        $coordinateY = $bestChild->getPosition()->getCoordinateY();
        $board->take(new Position($coordinateX, $coordinateY));
        $this->responseData->x = $coordinateX;
        $this->responseData->y = $coordinateY;
        $this->entityManager->flush();
    }
}