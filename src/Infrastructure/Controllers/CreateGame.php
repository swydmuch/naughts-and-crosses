<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateGame extends AbstractController
{
    public function execute(Request $request): Response
    {
        return new Response('start new game');
    }
}