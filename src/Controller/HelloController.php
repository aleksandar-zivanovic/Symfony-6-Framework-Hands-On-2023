<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    private array $messages = ['Hi', 'Hello', 'Bye!'];

    #[Route('/{limit}', name: 'app_index', requirements: ['limit' => '\d+'])]
    public function index(int $limit = 1): Response
    {
        return new Response(implode(', ', array_slice($this->messages, 0, $limit)));
    }

    #[Route('/messages/{id}', name: 'app_show_one', requirements: ['id' => '\d+'])]
    // #[Route('/messages/{id<\d+>}', name: 'app_show_one')] // - isto kao kod iznad
    public function showOne(int $id): Response
    {
        return new Response($this->messages[$id]);
    }
}
