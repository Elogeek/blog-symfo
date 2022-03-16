<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/change_locale/{locale}', name: 'change_locale')]
    public function changeLocale($locale, Request $request): Response {
        $request->getSession()->set('_locale', $locale);
        return $this->redirectToRoute("home");
    }
}