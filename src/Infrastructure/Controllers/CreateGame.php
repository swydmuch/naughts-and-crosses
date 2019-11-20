<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Controllers;

use NAC\Application\UseCases\CreateGame\Command;
use NAC\Application\UseCases\CreateGame\Handler;
use NAC\Infrastructure\Doctrine\EntityManager;
use NAC\Infrastructure\IdGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateGame extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(Request $request): Response
    {
        $boardSize = (int) $request->request->get('boardSize');
        $lineSize = (int) $request->request->get('lineSize');
        $startingPlayer =  (int) $request->request->get('startingPlayer');

        $id = IdGenerator::generate();
        $command = new Command($boardSize, $lineSize, $startingPlayer, $id);
        $handler = new Handler($this->entityManager);
        $handler->handle($command);

        return new JsonResponse(['id' => $id], 201);
    }
}