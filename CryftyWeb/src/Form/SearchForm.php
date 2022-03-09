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
            ->add('q',TextType::class,[
                'label'=> false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Rechercher',
                    'class'=>'filter__input'
                ]
            ])
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
            ->add('tri',ChoiceType::class,[
                'label'=>false,
                'required'=>false,
                'expanded'=>true,
                'multiple'=>false,
                'choices'=>[
                    'Tri par prix croissant'=>true,
                    'Tri par prix decroissant'=>false
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