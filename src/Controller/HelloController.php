<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello', 'created' => '2022/09/12'],
        ['message' => 'Hi', 'created' => '2022/04/12'],
        ['message' => 'Bye!', 'created' => '2021/05/12']
    ];

    #[Route('/{limit}', name: 'app_index', requirements: ['limit' => '\d+'])]
    public function index(int $limit = 3): Response
    {
        return $this->render('hello/index.html.twig', [
            'messages' => $this->messages,
            'limit' => $limit,
        ]);
    }

    #[Route('/messages/{id}', name: 'app_show_one', requirements: ['id' => '\d+'])]
    // #[Route('/messages/{id<\d+>}', name: 'app_show_one')] // - isto kao kod iznad
    public function showOne(int $id): Response
    {
        // return new Response($this->messages[$id]);
        return $this->render('hello\showOne.html.twig', [
            'message' => $this->messages[$id],
        ]);
    }
}
