<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/{id}', name: 'app_comment')]
    public function index(Comment $comment, CommentRepository $commentRepository): Response {
        $comment = $commentRepository->findByCat($comment->getId());
        return $this->render('comment/index.html.twig', [
            'comment' => $comment,
        ]);

    }
}
