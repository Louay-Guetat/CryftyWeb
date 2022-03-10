<?php

namespace App\Form;

use App\Entity\Crypto\Node;
use App\Entity\Crypto\Wallet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalletSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('walletAddress',TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr' => [
                    'placeholder' => 'Address'
                ]
            ])
            ->add('walletLabel',TextType::class,[
                'required'=>false,
                'label'=>false,
                'attr' => [
                    'placeholder' => 'Label'
                        ]
                    ])
            ->add('isActive',CheckboxType::class,[
                'label' => 'Active',
                'required' => false,
            ])
            ->add('isMain',CheckboxType::class,[
                'label' => 'Main',
                'required' => false
            ])
            ->add('nodeId',EntityType::class,[
                'label' => false,
                'placeholder' => 'No Specific Node',
                'choice_label' => 'nodeLabel',
                'required' => false,
                'class' => Node::class,
                'expanded' => false,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix(): string
    {
        return '';
    }
}
