<?php

namespace App\Form;

use App\Entity\Crypto\Node;
use App\Entity\Crypto\Wallet;
use App\Entity\Payment\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant',MoneyType::class,[
                'label'=>"Montant"
            ,'label_attr'=>['class'=>'sign__label']
            ,'attr'=>['class'=>'sign__input']
            ])
            ->add('wallets',EntityType::class,[
                'class'=>Wallet::class,
                'choice_label'=>'walletAddress',
                'label'=>"wallets"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
                ,
            ])
            ->add('Payer', SubmitType::class, ['label'=>"Payer",
                'attr' => ['class' => 'sign__btn'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
