<?php

namespace App\Form;
use App\Entity\Recipes;
use App\Entity\Ingredient;
use App\Repository\RecipesRepository;
use App\Repository\IngredientRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Vich\UploaderBundle\Form\Type\VichImageType;

class RecipesType extends AbstractType
{
private Security $security;
    private $token;

    public function __construct(TokenStorageInterface $token,Security $security)
    {
       $this->token = $token;
       $this->security = $security;
    }
    
    

   

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Recipe Name',
            'required' => true,

            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 255]),
                new Assert\NotBlank()
            ]
            
        ])


     
        ->add('time', IntegerType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Cooking Time (minutes)',

            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotNull(),
             
                new Assert\Positive(),
                new Assert\LessThan(1441)
            ]
        ])


        
        ->add('nbpersonne', IntegerType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Number of People',

            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
               
                new Assert\NotBlank(),
                new Assert\NotNull(),
               new Assert\Positive(),
               new Assert\LessThan(51)
            ]
        ])



    
        ->add('difficulte', ChoiceType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Difficulty',
            'choices' => [
                'Easy' => 1,
                'Medium' => 2,
                'Hard' => 3,
            ],
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\LessThan(6)
            ]
        ])


        ->add('discription', TextareaType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Description',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
              
                new Assert\NotBlank()
            ]
        ])
        ->add('price', NumberType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Price',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Positive()
            ]
        
        ])

        ->add('isFavorite', CheckboxType::class, [
            'attr' => ['class' => 'form-check-input'],
            'label' => 'Favorite Recipe',
            'required' => false,

            'label_attr' => [
                'class' => 'form-check-label '
            ],
            'constraints' => [
               
                new Assert\NotNull()
            ]
        ])

        ->add('imageFile',VichImageType::class,[
            
            'label' => 'Image de la recettre',
            'label_attr' => ['class' => 'form-control mt-4']
        ] )

        ->add('ingredient', EntityType::class, [
            'attr' => ['class' => 'form-control'],
            'class' => Ingredient::class, // Replace with your entity class
            'query_builder' =>function (IngredientRepository $r){
                return $r->createQueryBuilder('i')
                        ->where('i.user=:user')
                        ->orderBy('i.nom', 'asc')
                        ->setParameter('user', $this->token->getToken()->getUser());
                    },
            'choice_label' => 'nom', // Replace with the property of your entity to be used as the label
            'multiple' => true,
            'expanded' => true,
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Count(min: 1)
            ]
        ])
 



        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary mt-4'],
           
            'label' => 'Créer ',
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
