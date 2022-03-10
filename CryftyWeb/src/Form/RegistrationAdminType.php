<?php

namespace App\Form;

use App\Entity\Users\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,['label'=>"Username"
                ,'label_attr'=>['class'=>'control-label col-lg-2']
                ,'attr'=>['class'=>'form-control']
            ])
            ->add('password',PasswordType::class,['label'=>"Password"
                ,'label_attr'=>['class'=>'control-label col-lg-2']
                ,'attr'=>['class'=>'form-control']
            ])
            ->add('firstName',TextType::class,['label'=>"FirstName"
                ,'label_attr'=>['class'=>'control-label col-lg-2']
                ,'attr'=>['class'=>'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
