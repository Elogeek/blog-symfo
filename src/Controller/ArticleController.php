<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article', name: 'article_')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(): Response {
        $articles = [
            new Article(),
            new Article(),
            new Article(),
            new Article(),
            new Article(),
        ];

        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);

    }

    #[Route('/{id<\d+>}', name: 'single')]
    public function index(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->addArticle($article)->setAuthor($this->getUser());
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        return $this->render('article/index.html.twig', [
            'article' => $article,
            "form" => $form->createView()

        ]);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
   #[Route('/add', name: 'add')]
    public function add(Category $category, Request $request, EntityManagerInterface $entityManager): Response {
        $article = new Article();
        $article->setCategory($category)->setDatePostArticle(new \DateTime())->setAuthor($this->getUser());
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('article/add.html.twig', [
                'form' => $form->createView(),
                'id' => $category->getId(),
            ]
        );
    }


}
