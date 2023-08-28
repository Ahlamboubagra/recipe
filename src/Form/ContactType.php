<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fullName', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minLength' => '2',
                'maxLength' => '50',
            ],
            'label' => 'Nom /Prénom',
            'label_attr' => [
                'class' => 'form-Label mt-4',
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 255]),
                new Assert\NotBlank(),
            ],
        ])
        ->add('subject', TextType::class, [
            'attr' => [
                'class' => 'form-control', 
            ],
             'required' => false,
            'label' => 'subject',
            'label_attr' => [
                'class' => 'form-Label mt-4',
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 50]),
               
            ],
        ])
        ->add('email', EmailType::class, [
            'attr' => [
                'class' => 'form-control',
                'minLength' => '2',
                'maxLength' => '180',
            ],
            'label' => 'Adress email',
            'label_attr' => [
                'class' => 'form-Label mt-4',
            ],
            'constraints' => [
                new Assert\Email(),
                new Assert\NotBlank(),
                new Assert\Length(['min' => 2, 'max' => 180]),
            ],
        ])

        ->add('message', TextareaType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'message',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
              
                new Assert\NotBlank()
            ]
        ])
        
        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary mt-4'],
            'label' => 'submit'
        ])
        ->add('captcha', Recaptcha3Type::class, [
            'attr' => ['class' => 'btn btn-primary mt-4'],
            // 'constraints' => new Recaptcha3(),
            // 'action_name' => 'contact',
            // 'attr' => ['class' => 'form-control'],
        ])


        ;
    
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
