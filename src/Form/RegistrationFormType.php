<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Diet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Ajout d'un utilisateur.
            ->add('username', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide'
                    ]),
                    new Length([
                        'min' => 3,
                        // ici limit ce reference à la variable 'min'.
                        'minMessage' => 'Votre nom d\'utilisateur doit contenir au moins {{ limit }} caractères.', 
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                /* au lieu d'être placé directement sur l'objet,
                   ceci est lu et encodé dans le contrôleur*/
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // longueur maximale autorisée par Symfony pour des raisons de sécurité.
                        'max' => 4096,
                    ]),
                ],
            ])
            // Ajout du régime en recuperant en BDD.
            ->add('diet', EntityType::class, [
                'class' => Diet::class,
                'choice_label' => 'dietName',
                'label' => 'Regime:',
                'placeholder' => 'Choisissez un régime',
                'choice_value' => function (Diet $entity = null) 
                {
                    return $entity ? $entity->getDietName() : '';
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}