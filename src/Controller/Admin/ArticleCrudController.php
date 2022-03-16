<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

class ArticleCrudController extends AbstractCrudController
{
    // shortcut path file upload img
    public const PRODUCTS_BASE_PATH = 'build/image/uploads/recipes';
    public const PRODUCTS_UPLOAD_DIR = 'public/build/image/uploads/recipes';

    //shortcut copy product
    public const ACTION_DUPLICATE = 'duplicate';

    public static function getEntityFqcn(): string {
        return Article::class;
    }

    /**
     * Add btn copy/duplicate article in the area admin
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions {
        //copy article
        $duplicate = Action::new(self::ACTION_DUPLICATE)
            ->linkToCrudAction('duplicateArticle')
            ->setCssClass('btn btn-info');

        return $actions
            ->add(Crud::PAGE_EDIT, $duplicate)
            // Place btn selon où on décide
            ->reorder(Crud::PAGE_EDIT,[self::ACTION_DUPLICATE, Action::SAVE_AND_RETURN]);
    }

    /**
     * S'occupe des champs du form in the area admin
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'titre'),
            SlugField::new('slug')->setTargetFieldName('title'),
            ChoiceField::new('level')->setChoices([
                'Facile' => "Facile",
                'Moyen' => "Moyen",
                'Difficile' => "Difficile",
            ]),
            TextEditorField::new('content', 'description'),

            // Filtre les categories non actives ( hidden)
            BooleanField::new('active'),
            AssociationField::new('category')->setQueryBuilder(function (QueryBuilder $queryBuilder) {
                $queryBuilder->where('entity.active = true');
            }),
            // Gere l'image de l'article
            ImageField::new('picture')
                ->setBasePath(self::PRODUCTS_BASE_PATH)
                ->setUploadDir(self::PRODUCTS_UPLOAD_DIR)
                ->setSortable(false),

            TextField::new('preparationTime'),

            // Here date add article and date update article
            DateTimeField::new('updatedAt')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),


        ];
    }

    /**
     * lors de l'ajout de l'article, ajoute une date de création de celui-ci
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void {
        if(!$entityInstance instanceof Article) return;
        $entityInstance->setCreatedAt(new \DateTimeImmutable);
        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     *  Update  the data article in the admin area
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void {
        if(!$entityInstance instanceof Article) return;
        $entityInstance->setUpdatedAt(new \DateTimeImmutable);
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Copy article in the admin area
     * @param AdminContext $adminContext
     * @param EntityManagerInterface $entityManager
     * @param AdminUrlGenerator $adminUrlGenerator
     * @return Response
     */
    public function duplicateArticle(AdminContext $adminContext, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): Response {
        /**@var Article $article */
        $article = $adminContext->getEntity()->getInstance();
        $duplicateArticle = clone $article;
        parent::persistEntity($entityManager, $duplicateArticle);

        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicateArticle->getId())
            ->generateUrl();

        return $this->redirect($url);
    }

}
