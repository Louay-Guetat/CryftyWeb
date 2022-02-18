<?php

namespace App\Form;

use App\Entity\NFT\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AjoutSubCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['label'=>"SubCategory name"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
                ,'constraints'=>array(new NotBlank(['message'=>'Ce champ ne doit pas etre vide'])
                , new Length(['min'=>3,'max'=>20]))
            ])
            ->add('Category',EntityType::class,[
                'required' => false,
                'label' => 'Category Id',
                'class' => Category::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
