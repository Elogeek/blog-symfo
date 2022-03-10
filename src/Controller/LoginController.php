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
    public function login(AuthenticationUtils $authenticationUtils): Response {
        // If the user is already connected => redirect home page of the site
        if($this->getUser()) {
            return  $this->redirectToRoute('home');
        }
        // Error recovery if any
        $error = $authenticationUtils->getLastAuthenticationError();
        // Retrieval of the last connected user
        $emailUser = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $emailUser,
            'error' => $error,
        ]);
    }

    /**
     * Disconnect a user
     **/
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void {}

}
