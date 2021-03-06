<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Controllers;

use NAC\Application\UseCases\GetGameStatus\Query;
use NAC\Application\UseCases\GetGameStatus\Handler;
use NAC\Infrastructure\Doctrine\Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GameStatus extends AbstractController
{
    private $boardRepository;

    public function __construct(Repository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    public function execute(string $boardId): Response
    {
        $query = new Query($boardId);
        $handler = new Handler($this->boardRepository);
        $gameStatus = $handler->handle($query);

        return new JsonResponse(['status' => $gameStatus->getNameOfStatus()]);
    }
}