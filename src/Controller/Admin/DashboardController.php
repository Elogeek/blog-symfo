<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {}

    /**
     * Gere the admin page
     * @return Response
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response {
        // Display list article
       $url = $this->adminUrlGenerator
           ->setController(ArticleCrudController::class)
           ->generateUrl();
        return $this->redirect($url);
    }

    /**
     * Title of the dashboard
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard {
        return Dashboard::new()
            ->setTitle('Zen Cook');
    }

    public function configureMenuItems(): iterable {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // Section article
        yield MenuItem::section('Articles');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create article', 'fas fa-plus',Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show articles', 'fas fa-eye',Article::class)
        ]);
        // Section category
        yield MenuItem::section('Categories');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create category', 'fas fa-plus',Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show categories', 'fas fa-eye',Category::class)
        ]);
        // Section users
        yield MenuItem::section('User');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Show users', 'fas fa-eye',User::class)
        ]);
        // Section comments
        yield MenuItem::section('Comments');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create comment', 'fas fa-comment',Comment::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show comments', 'fas fa-eye',Comment::class)
        ]);
    }

}
