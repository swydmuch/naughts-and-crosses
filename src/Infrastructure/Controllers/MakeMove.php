<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Controllers;

use NAC\Application\UseCases\MakeMove\Command;
use NAC\Application\UseCases\MakeMove\Handler;
use NAC\Infrastructure\Doctrine\EntityManager;
use NAC\Infrastructure\Doctrine\Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MakeMove extends AbstractController
{
    private $boardRepository;
    private $entityManager;

    public function __construct(Repository $boardRepository, EntityManager $entityManager)
    {
        $this->boardRepository = $boardRepository;
        $this->entityManager = $entityManager;
    }

    public function execute(Request $request, string $boardId): Response
    {
        $requestContent = json_decode($request->getContent());
        $coordinateX = $requestContent->x;
        $coordinateY = $requestContent->y;

        $command = new Command($coordinateX, $coordinateY, $boardId);
        $handler = new Handler($this->boardRepository, $this->entityManager);
        $handler->handle($command);

        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }
}