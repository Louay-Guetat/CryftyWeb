<?php

namespace App\Form;

use App\Entity\Crypto\Transfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount',NumberType::class,[
                'label'=>"Amount to send",
            'label_attr'=> ['class'=>'sign__label'],
                'attr' => ['class'=>'sign__input'],
                'constraints'=>array(new NotBlank(['message'=>'Enter an address to send to']))

            ])
            ->add('senderId',TextType::class,[
                'label'=>"Your Address",
                'label_attr'=> ['class'=>'sign__label'],
                'attr' => ['class'=>'sign__input'],
                'constraints'=>array(new NotBlank(['message'=>'Enter an address to send to']),
                    new Length(['min'=>34,'max'=>34]))

            ])
            ->add('recieverId',TextType::class,[
                'label'=>"Send To",
                'label_attr'=> ['class'=>'sign__label'],
                'attr' => ['class'=>'sign__input'],
                'constraints'=>array(new NotBlank(['message'=>'Enter an address to send to']))
            ])
            ->add('submit',SubmitType::class,[
                'attr'=>['class'=>'sign__btn'],
                'label'=>'Transfer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
