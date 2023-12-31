<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use DateTime;
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

    

    #[Route('/pg', name: 'app_playground')]
    public function playground(MicroPostRepository $posts, CommentRepository $comments): Response
    {

        // $post = $posts->find(12);
        // $comment = $post->getComments()[0];
        // $comments->remove($comment, true);

        // $post = new MicroPost();
        // $post->setTitle('Hello');
        // $post->setText('Hello');
        // $post->setCreated(new DateTime());

        // $comment = new Comment();
        // $comment->setText('Hello without cascada option');
        // $comment->setPost($post);
        // // $post->addComment($comment);
        // // $posts->save($post, true);
        // $comments->save($comment, true);

        // $comment = $comments->find(2);
        // $comment->setText('Hello with cascada:persist');
        // $comments->save($comment, true);

        // $user = new User();
        // $user->setEmail('pera@pera.com');
        // $user->setPassword(111111);

        // $userProfile = new UserProfile();
        // $userProfile->setUser($user);
        // $userProfileRepository->save($userProfile, true);

        // $user = $userRepository->find(3);
        // $userRepository->remove($user, true);

        // $userProfile = $userProfileRepository->find(3);
        // $userProfileRepository->remove($userProfile, true);
        
        return $this->render('hello/index.html.twig', [
            'messages' => 'app_playground',
            'limit' => 0,
        ]);
    }
}
