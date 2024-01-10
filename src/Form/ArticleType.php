<?php
 
namespace App\Form;

use App\Entity\Article;
use App\Entity\Diet;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        // Titre
        $builder->add('title', TextType::class, [
            'label' => 'Nom de la recette:',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ ne peut être vide'
                ])
            ]
        ]);

        //imageUpload
        $builder->add('imagePlat', FileType::class, [
                'label' => 'image du plat (jpg, jpeg, png uniquement):',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Téléversez une image valide',
                    ])
                ],
        ]);

        // Contenu
        $builder->add('content', TextareaType::class, [
            'label' => 'Contenu du plat:'
        ]);

        $builder->add('dietFood', EntityType::class, [
            'class' => Diet::class,
            'choice_label' => 'dietName',
            'label' => 'Régime du plat:',
            'choice_value' => function ($entity)
            {
                return $entity;
            }
        ]);

        // Statut
        $builder->add('isPublished', CheckboxType::class, [
            'label' => 'Publier l\'article'
        ]);

        // Bouton Envoyer
        $builder->add('submit', SubmitType::class, array(
            'label' => 'Enregistrer'
        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

}