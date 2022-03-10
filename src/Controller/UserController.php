<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
}