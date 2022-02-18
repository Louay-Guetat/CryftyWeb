<?php

namespace App\Form;

use App\Entity\Crypto\Node;
use App\Entity\Crypto\Wallet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('walletLabel', TextType::class,[
                'label_attr'=> ['class'=>'sign__label'],
                'attr' => ['class'=>'sign__input','placeholder'=>"Your Label Here ! "],

            ])
            ->add('nodeId', EntityType::class,[
                'class' => Node::class,
                'choice_label' => 'node_label',
                'label' => 'Choose your Wallet Type',
                'attr'=>['class'=>'sign__select'],
                'label_attr'=>['class'=>'sign__label']

            ])
            ->add('submit',SubmitType::class,[
                'attr'=>['class'=>'sign__btn'],
                'label'=>'Create Wallet'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class,
        ]);
    }
}
