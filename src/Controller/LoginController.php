<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * Connect a user
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()) {
            return  $this->redirectToRoute('home');
        }
        // Récup des errors s'il y en a
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récup du dernier user connecté
        $emailUser = $authenticationUtils->getLastAuthenticationError();
        return $this->render('login/index.html.twig', [
            'email_user' => $emailUser,
            'error' => $error,
        ]);
    }

    /**
     * Disconnect a user
     **/
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void {}

}
