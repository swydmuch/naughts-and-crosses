<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Controllers;

use NAC\Application\UseCases\AIMove\Command;
use NAC\Application\UseCases\AIMove\Handler;
use NAC\Infrastructure\Doctrine\EntityManager;
use NAC\Infrastructure\Doctrine\Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AIMove extends AbstractController
{
    private $boardRepository;
    private $entityManager;

    public function __construct(Repository $boardRepository, EntityManager $entityManager)
    {
        $this->boardRepository = $boardRepository;
        $this->entityManager = $entityManager;
    }

    public function execute(string $boardId): Response
    {
        $command = new Command($boardId);
        $responseData = new \stdClass();
        $handler = new Handler($this->boardRepository, $this->entityManager, $responseData);
        $handler->handle($command);

        return new JsonResponse([
            'x' => $responseData->x,
            'y' => $responseData->y
        ]);
    }
}