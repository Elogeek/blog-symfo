<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
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
            // Prend l'image avatar en url mais je préfère l'ImageField
            //AvatarField::new('avatar')->setIsGravatarEmail(),
            // Gere l'image de l'article
            ImageField::new('avatar')
                ->setBasePath('build/upload/images/users/avatars')
                ->setUploadDir('public/build/upload/images/users/avatars')
                ->setSortable(false),
            // Display role user in the area admin
           // NumberField::new('roles')
            //->setNumberFormat(self::getEntityFqcn()),

            TextField::new('password')->hideOnForm(),
        ];
    }


}
