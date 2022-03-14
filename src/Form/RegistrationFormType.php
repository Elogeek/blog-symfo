<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;


class RegistrationFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Votre email",
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => "Adresse mail trop courte",
                        'max'=> 50,
                        'maxMessage' => "Adresse mail trop longue"
                    ]),
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => [
                    'label' => 'Entrez un mot de passe'
                ],
                'second_options' => [
                    'label' => 'Répétez votre mot de passe'
                ],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],

                'constraints' => [
                    new NotCompromisedPassword([
                        'message' => 'Le mot de passe est trop facile, veuillez le modifier !'
                    ])
                ]
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Upload a avatar',
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}