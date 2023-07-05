<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {

        // $microPost = new MicroPost();
        // $microPost->setTitle('Created from the controller!');
        // $microPost->setText('Hi!');
        // $microPost->setCreated(new DateTime());
        // $posts->save($microPost, true);

        // $microPost = $posts->find(2);
        // $microPost->setTitle('Welcome to Disneyland!');
        // $posts->save($microPost, true);

        // $microPost = $posts->find(4);
        // $posts->remove($microPost, true);

        // dd($posts->findOneBy(['title' => 'Welcome to US!']));
        
        return $this->render('micro_post/index.html.twig', [
            'controller_name' => 'MicroPostController',
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show', requirements: ['id' => '\d+'])]
    public function showOne(MicroPost $post)
    {
        dd($post);
    }
}
