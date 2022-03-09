<?php

namespace App\Form;

use App\Entity\Crypto\Node;
use App\Entity\NFT\Category;
use App\Entity\NFT\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ModifierNftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,['label'=>"TITLE"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
                ,'constraints'=>array(new NotBlank(['message'=>'Ce champ ne doit pas etre vide'])
                , new Length(['min'=>3,'max'=>20]))
            ])
            ->add('description',TextareaType::class,['label'=>"DESCRIPTION"
                ,'label_attr'=>['class'=>'sign__label']
                ,'required'=>false
                ,'attr'=>['class'=>'sign__textarea','cols' => '5', 'rows' => '5']
            ])
            ->add('price',NumberType::class,['label'=>"PRICE"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
                ,'constraints'=>array(new NotNull(['message'=>'Ce champ ne doit pas être vide']))
            ])
            ->add('currency',EntityType::class,
                [
                    'required' => false,
                    'label' => 'Currency',
                    'class' => Node::class,
                    'multiple' => false,
                    'expanded' => false,
                    'choice_label' => 'coinCode'
                    ,'label_attr'=>['class'=>'sign__label']
                    ,'attr'=>['class'=>'sign__select']
                    ,'constraints'=>array(new NotNull(['message'=>'Ce champ ne doit pas être vide']))

                ])
            ->add('category',EntityType::class,[
                'required' => false,
                'label' => 'Category',
                'class' => Category::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name'
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__select']
                ,'constraints'=>array(new NotNull(['message'=>'Ce champ ne doit pas être vide']))

            ])
            ->add('subcategory',EntityType::class,[
                'required' => false,
                'label' => 'SubCategory',
                'class' => SubCategory::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name'
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__select','id'=>'test']
            ]);

        $formModifier = function (FormInterface $form, Category $category=null){
            $subCategories = (null === $category) ? [] : $category->getSubCategories();
            $form->add('subcategory',EntityType::class,[
                'class'=>SubCategory::class,
                'choices'=> $subCategories,
                'label' => 'SubCategory',
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name'
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__select','id'=>'test']
            ]);
        };

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier){
                $cat = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(),$cat);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
