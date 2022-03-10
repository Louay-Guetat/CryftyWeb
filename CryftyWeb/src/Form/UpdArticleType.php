<?php

namespace App\Form;

use App\Entity\Blog\BlogArticle;
use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToRfc3339Transformer;
//use Symfony\Component\Form\Extension\Core\DataTransformer\BaseDateTimeTransformer;
//use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
//use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


//use Symfony\Component\Validator\Constraints\DateTime;

class UpdArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title',TextType::class,[
                'label'=>'title Article',
                'attr'=>[
                    'placeholder'=>'Merci de définir le titre'
                ]
            ])
            //->add('contents',TextType::class)
            ->add('contents',CKEditorType::class,[
                'label'=>'Contents',
                'attr'=>[
                    'placeholder'=>'content'
                ]
            ])
            //->add('category',TextType::class)
            ->add('category',ChoiceType::class,[
                'choices'=> array(
                    'Art'=>'Art',
                    'Games'=>'Games',
                    'photography'=>'photography',
                    'Music'=>'Music',
                    'Crypto'=>'Crypto',
                    'Memes'=>'Memes'
                )
            ])
            //->add('author',TextType::class)
            ->add('author',TextType::class,[
                'label'=>'Author',
                'attr'=>[
                    'placeholder'=>'Author'
                ]
            ])
            //->add('date',TextType::class)
            ->add('date', DateType::class,[
                'attr' => ['class' => 'form-control '],

                'widget' =>'single_text',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class'=> BlogArticle::class,

            // Configure your form options here
        ]);
    }
}
