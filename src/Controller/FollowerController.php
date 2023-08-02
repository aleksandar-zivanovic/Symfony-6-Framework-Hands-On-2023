<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowerController extends AbstractController
{
    #[Route('/follow/{id}', name: 'app_follow')]
    public function follow(User $userToFollow, Request $request, ManagerRegistry $doctrine): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser !== $userToFollow) {
            $currentUser->follow($userToFollow);
            $doctrine->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unfollow/{id}', name: 'app_unfollow')]
    public function unfollow(User $userToUnfollow, Request $request, ManagerRegistry $doctrine): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser !== $userToUnfollow) {
            $currentUser->unfollow($userToUnfollow);
            $doctrine->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
