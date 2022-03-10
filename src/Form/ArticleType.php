<?php

namespace App\Form;

use App\Entity\Article;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('picture')
            // Add category article
            ->add('category')
            // Add author article
            ->add('author')
            // Add comments article
            ->add('comments')
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
        ]);
    }
}
