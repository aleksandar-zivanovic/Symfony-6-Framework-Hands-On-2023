<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComments(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show', requirements: ['id' => '\d+'])]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    // #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Request $request, MicroPostRepository $microPostRepository): Response
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // dd($this->getUser()->getRoles());
        $microPost = new MicroPost();
        $form = $this->createFormBuilder($microPost)
            ->add('title')
            ->add('text')
            // ->add('submit', SubmitType::class, ['label' => 'save'])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser());
            $microPostRepository->save($post, true);

            // Add a flash
            $this->addFlash('success', 'Your micro post has been added!');

            // Redirect
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render('micro_post/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(MicroPost $post, Request $request, MicroPostRepository $microPostRepository): Response
    {
        // $form = $this->createFormBuilder($post)
        //     ->add('title')
        //     ->add('text')
        //     // ->add('submit', SubmitType::class, ['label' => 'save'])
        //     ->getForm();

        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $microPostRepository->save($post, true);

            // Add a flash
            $this->addFlash('success', 'Your micro post has been updated!');

            // Redirect
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render('micro_post/edit.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(MicroPost $post ,Request $request, CommentRepository $comments): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $comments->save($comment, true);

            // Add a flash
            $this->addFlash('success', 'Your comment has been added!');

            // Redirect
            return $this->redirectToRoute('app_micro_post_show', [
                'post' => $post->getId(),
            ]);
        }

        return $this->render('micro_post/comment.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }

    // #[Route('/micro-post/{post}/test', name: 'app_micro_post_test')]
    // public function test(MicroPost $post): void
    // // MicroPost $post povalči post iz baze na osnovu {post} parametra iz #[Route('/micro-post/{post}/test', ...)]
    // {
    //     dd($post->getComments()->getValues());
    // }
}
