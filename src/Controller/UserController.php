<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[isGranted('IS_AUTHENTICATED_FULLY')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(UserRepository $userRepository,User $user): Response {

        if ($this->isGranted("ROLE_ADMIN")) {
            $users = $userRepository->findAll();
        } else {
            $users = $userRepository->findBy(['roles' => ['ROLE_USER']]);
        }
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/update/{id}', name:'app_update_user')]
    public function update(User $user, EntityManagerInterface $entityManager): Response {
        $user
            ->setEmail('')
            ->setAvatar('')
        ;

        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    #[Route('/user/delete/{id}', name:'app_delete_user')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response {
     $entityManager->remove($user);
     $entityManager->flush();
     return $this->redirectToRoute("home");
    }

}