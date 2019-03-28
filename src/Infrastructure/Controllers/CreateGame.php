<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Controllers;

use NAC\Application\UseCases\CreateGame\Command;
use NAC\Application\UseCases\CreateGame\Handler;
use NAC\Infrastructure\Doctrine\EntityManager;
use NAC\Infrastructure\IdGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $boardSize = (int) $request->query->get('board_size');
        $lineSize = (int) $request->query->get('line_size');
        $startingPlayer = (int) $request->query->get('starting_player');
        $id = IdGenerator::generate();

        $command = new Command($boardSize, $lineSize, $startingPlayer, $id);
        $handler = new Handler($this->entityManager);
        $handler->handle($command);

        $response = new Response();
        $response->setStatusCode(201);
        $response->setContent(json_encode([
            'id' => $id,
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}