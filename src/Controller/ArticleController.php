<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article_list')]
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
            'app.user' => []
        ]);

    }

    #[Route('article/{id<\d+>}', name: 'article_index')]
    public function index(Article $article, Request $request, EntityManagerInterface $entityManager): Response {
        // If user already connect
        if($this->getUser()) {
            $user = $this->getUser()->getUserIdentifier();
        }
        else {
            $user = "";
        }

        $comment = new Comment();
        $comment->addArticle($article)->setAuthor($this->getUser());
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        // Completed form and data correct
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('article/index.html.twig', [
            'article' => $article,
            "form" => $form->createView(),
            "user" => $user,

        ]);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
   #[Route('article/add', name: 'article_add')]
    public function add(Category $category, Request $request, EntityManagerInterface $entityManager,ParameterBagInterface $parameterBag): Response {
        $article = new Article();
        $date = new \DateTime();
        // Récupération de l'user connecté
        $user = $this->getUser();

        $article->setCategory($category)->setCreatedAt($date)->setAuthor($user);
        $form = $this->createForm(ArticleType::class, $article, [
            // If not author => null
            'default_author' => $entityManager->getRepository(User::class)->find(3),
            // Choice author
            'users' => $entityManager->getRepository(User::class)->findAll(),
            'categories'=>$entityManager->getRepository(Category::class)->findAll(),
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // upload image of the article
            $file = $form['picture']->getData();
            $ext = $file->guessExtension();
            if(!$ext) {
                // If not ext alors use ext générique
                $ext = ['png', 'jpeg', 'webp'];
            }
            // Check save normal clicked
            $published = $form->get('save')->isClicked();
            $article->setIsPublished($published);

            // Déplacement du file and rename name unique
            $file->move($parameterBag->get("/image/uploads/recipes"), uniqid(). "." .$ext);
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash("success", "Article create with success !");

            return $this->redirectToRoute('app_category',["id" => $category->getId()]);
        }

        return $this->render('article/add.html.twig', [
                'form' => $form->createView(),
                'categories' => [],
                'users' => [],
                'default_author' => null,
            ]
        );
    }

    #[Route('/article/delete/{id}', name:'app_delete_article')]
    #[IsGranted('ROLE_AUTHOR')]
    public function delete(Article $article, EntityManagerInterface $entityManager): Response {
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirectToRoute("home");
    }

}
