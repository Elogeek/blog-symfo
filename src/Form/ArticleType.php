<?php

namespace App\Form;

use App\Entity\Article;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Add title article
            ->add('title', TextType::class, [
                'label' => "Article title", "Titre de l'article",
            ])
            // Add slug of the article
            ->add('slug', TextType::class, [
                'label' => "Article slug", "Slug d'article",
            ])
            // Added recipe level
            ->add('level', ChoiceType::class, [
                'Facile' => "Facile",
                'Moyen' => "Moyen",
                'Difficile' => "Difficile",
            ])
            // Add PreparationTime
            ->add('preparationTime', TextType::class,[
                'label' => 'indicate the total time of the recipe', 'Indiquer le temps total de la recette'
            ])
            // Add content article
            ->add('content', TextareaType::class, [
                'label' => "Enter the content of the article","Entrez le contenu de l'article",
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
                'mapped' => false,
                'required' => false,
                'constraints' => ([
                    new Image([
                        'maxSize' => '5M',
                        'maxSizeMessage' => "L'image est trop lourde!",
                        'detectCorrupted' => true
                    ])
                ])
            ])
            // Add category article
            ->add('category',EntityType::class, [
                'class' => Category::class,
                'label' => "Choose a category","Choisissez une catégorie",
                'placeholder' => "Choose a category",
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
                'group_by' => function(User $user){
                    if (in_array('ROLE_ADMIN', $user->getRoles())) {
                        return 'Admins:';
                    }
                    elseif(in_array('ROLE_AUTHOR', $user->getRoles())) {
                        return 'Auteurs:';
                    }
                    return 'Autres:';
                }
            ])
            // Refresh article
            ->add('reset', ResetType::class, [
                'label' => "Recommencer"
            ])
            // Btn submit article
            ->add('save', SubmitType::class,[
                'label' => "Save"
            ])
            // Sava as draft
            ->add('save_draft', SubmitType::class,[
                'label' => "Save as draft"
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
