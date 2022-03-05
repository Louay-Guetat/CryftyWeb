<?php

namespace App\Form;

use App\Entity\Chat\GroupChat;
use App\Entity\Users\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['label'=>" Group name"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input','id'=>'nom']

                ,'constraints'=>array( new Length(['min'=>3,'max'=>20]))
            ])

          ;



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupChat::class,
        ]);
    }
}
