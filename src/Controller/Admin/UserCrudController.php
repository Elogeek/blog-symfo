<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string {
        return User::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email', 'email'),
            // Gere l'image de l'avatar de l'user
            ImageField::new('avatar')
                ->setBasePath('build/image/avatar')
                ->setUploadDir('public/build/image/avatar')
                ->setSortable(false),
            // Display role user in the area admin
            ArrayField::new('roles')
            ->addHtmlContentsToBody(self::getEntityFqcn($this->getUser()->getRoles())),
            // Hash password
            TextField::new('password'),
        ];
    }
}
