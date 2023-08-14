<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Form\ProfileImageType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SettingsProfileController extends AbstractController
{
    #[Route('/settings/profile', name: 'app_settings_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profile(Request $request, UserRepository $users): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userProfile = $user->getUserProfile() ?? new UserProfile();
        
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile = $form->getData();
            $user->setUserProfile($userProfile);
            $users->save($user, true);
            $this->addFlash('success', 'Your user prifile settings are saved.');
            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render('settings_profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/settings/profile-image', name: 'app_settings_profile_image')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profileImage(Request $request, UserRepository $users, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProfileImageType::class);
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $profileImageFIle = $form->get('profileImage')->getData();
            
            if ($profileImageFIle) {
                $originalFileName = pathinfo($profileImageFIle->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . "-" . uniqid() . "." . $profileImageFIle->guessExtension();
                
                try {
                    $profileImageFIle->move(
                        $this->getParameter('profiles_directory'), 
                        $newFileName);
                } catch (FileException $e) {
                    
                }

                $profile = $currentUser->getUserProfile() ?? new UserProfile();
                $profile->setImage($newFileName);
                $currentUser->setUserProfile($profile);
                $users->save($currentUser, true);
                $this->addFlash('success', 'Your profile image is updated.');

                return $this->redirectToRoute('app_settings_profile_image');
            }
        }

        return $this->render('settings_profile/profile_image.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
