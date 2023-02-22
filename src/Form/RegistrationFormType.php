<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email',EmailType::class ,[
            'constraints' => [
                new NotBlank(['message' => 'Veuillez renseigner ce champ .']),
                new Regex(['pattern'=>'/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
               'message'=>'L\'email {{ value }} n\'est pas un email valide.',]),
            ],
        ])
        ->add('confirm_password',PasswordType::class)
        ->add('password',PasswordType::class)
        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez renseigner ce champ .']),
                new Length(['min' => 4, 'minMessage' => 'Veuillez avoir au moins {{ limit }} caractères','max' => 12, 'maxMessage' => 'Veuillez avoir au max {{ limit }} caractères']),
            ],
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez renseigner ce champ .']),
                new Length(['min' => 4, 'minMessage' => 'Veuillez avoir au moins {{ limit }} caractères','max' => 12, 'maxMessage' => 'Veuillez avoir au max {{ limit }} caractères']),
            ],
        ])
        ->add('tel',TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez renseigner ce champ .']),
                new Length(['min' => 8, 'max' => 8, 'exactMessage' => 'Cette champ doit comporter exactement 8 caractères']),
            ],
        ])
        ->add('adresse', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez renseigner ce champ .']),
                new Length(['min' => 4, 'minMessage' => 'Veuillez avoir au moins {{ limit }} caractères','max' => 12, 'maxMessage' => 'Veuillez avoir au max {{ limit }} caractères']),
            ],
        ])
            ->add('Enregistrer', SubmitType::class)
        
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            
          ;          
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
