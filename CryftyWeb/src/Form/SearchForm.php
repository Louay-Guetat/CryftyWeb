<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Crypto\Node;
use App\Entity\NFT\Category;
use App\Entity\NFT\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories',EntityType::class,[
                'label'=>false,
                'required'=>false,
                'class'=>Category::class,
                'choice_label' => 'name',
                'expanded'=>true,
                'multiple'=>true,
                'attr'=>[
                    'class'=>'filter__checkboxes'
                ]
            ])

            ->add('subCategories',EntityType::class,[
                'label'=>false,
                'required'=>false,
                'class'=>SubCategory::class,
                'choice_label' => 'name',
                'expanded'=>true,
                'multiple'=>true,
                'attr'=>[
                    'class'=>'filter__checkboxes'
                ]
            ])

            ->add('currency',EntityType::class,[
                'label'=>false,
                'required'=>false,
                'class'=>Node::class,
                'choice_label' => 'coinCode',
                'expanded'=>true,
                'multiple'=>true,
                'attr'=>[
                    'class'=>'filter__checkboxes'
                ]
            ])

            ->add('min',NumberType::class,[
                'label'=>false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Prix min',
                    'class'=>'filter__input'
                ]
            ])
            ->add('max',NumberType::class,[
                'label'=>false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Prix max',
                    'class'=>'filter__input'
                ]
            ])
            ->add('triPrix',ChoiceType::class,[
                'label'=>false,
                'required'=>false,
                'expanded'=>true,
                'multiple'=>false,
                'choices'=>[
                    'Tri par prix croissant'=>0,
                    'Tri par prix décroissant'=>1
                ],
                'attr'=>[
                    'class'=>'filter__checkboxes'
                ]
            ])

            ->add('triLikes',ChoiceType::class,[
                'label'=>false,
                'required'=>false,
                'expanded'=>true,
                'multiple'=>false,
                'choices'=>[
                    'Tri par pertinence croissante'=>0,
                    'Tri par pertinence décroissante'=>1
                ],
                'attr'=>[
                    'class'=>'filter__checkboxes'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>SearchData::class,
            'method'=>'GET',
            'csrf_protection'=>false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}