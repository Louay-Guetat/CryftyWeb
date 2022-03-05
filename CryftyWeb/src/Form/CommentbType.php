<?php

namespace App\Form;

use App\Entity\Blog\BlogComment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentbType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment',TextType::class,[
                'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input','placeholder' => 'Comment']
                ,'constraints'=>array(new NotBlank(['message'=>'This should not be empty']))
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class'=> BlogComment::class
            // Configure your form options here
        ]);
    }
}

