<?php

namespace App\Form;

use App\Entity\Article;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Add title article
            ->add('title', TextType::class, [
                'label' => "Titre de l'article",
            ])
            // Add content article
            ->add('content', TextareaType::class, [
                'label' => "Entrez le contenu de l'article",
            ])
            // Add dateAdd article
            ->add('datePostArticle',DateType::class, [
                'widget' => 'single_text',
                'label' => "Date d'ajout de l'article"
            ])
            // Add checkbox publishe article
            ->add('isPublished', CheckboxType::class, [
                'label' => "Publier l'article maitenant?",
            ])
            ->add('isPublished', RadioType::class, [
                'label' => "Publier l'article ",
            ])
            // Add picture article
            ->add('picture', FileType::class, [
                'label' => 'Uploadez votre image',
                'mapped' => false
            ])
            // Add category article
            ->add('category',EntityType::class, [
                'class' => Category::class,
                'label' => "Choisissez la bonne catégorie",
                'placeholder' => "Choisissez une catégorie",
                // Return a name category in the form
                'choice_label' => function(Category $category) {
                    return $category->getName();
                },
                'choices' => $options['categories']
            ])
            // Add author article
            ->add('author',EntityType::class, [
                'class' => User::class,
                'label' => "Choisissez l'auteur de l'article",
                'placeholder' => "Choisissez un auteur",
                // If user is null
                'empty_data' => $options['default_author'],
                'required' => false,
                // Return email user in the form
                'choice_label' => function(User $user) {
                    return $user->getEmail();
                },
                'choices' => $options['users']
            ])
            // Add comments article
            //->add('comments')
            // Refresh article
            ->add('reset', ResetType::class, [
                'label' => "Recommencer"
            ])
            // Btn submit article
            ->add('save', SubmitType::class,[
                'label' => "Sauvegarder l'article"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'categories' => [],
            'users' => [],
            'default_author' => null,
        ]);
    }
}
