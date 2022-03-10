<?php

namespace App\Form;

use App\Entity\Users\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class UpdateProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar',FileType::class,[
                'label' => 'Avatar Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5120K',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid .JPEG or .PNG image'
                    ])
                ],
                'attr' => ['class' => 'sign__file-upload']
            ])
            ->add('couverture',FileType::class,[
                'label' => 'Couverture Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5120K',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid .JPEG or .PNG image'
                    ])
                ],
                'attr' => ['class' => 'sign__file-upload']
            ])
            ->add('username',TextType::class,['label'=>"Username"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
            ])
            ->add('firstName',TextType::class,['label'=>"firstName"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
            ])
            ->add('lastName',TextType::class,['label'=>"LastName"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
            ])
            ->add('email',TextType::class,['label'=>"Email"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
            ])
            ->add('phoneNumber',TextType::class,['label'=>"phoneNumber"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
            ])
            ->add('age',TextType::class,['label'=>"age"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
            ])
            ->add('address',TextType::class,['label'=>"address"
                ,'label_attr'=>['class'=>'sign__label']
                ,'attr'=>['class'=>'sign__input']
            ])
            ->add('save', SubmitType::class, ['label'=>"save",
                'attr' => ['class' => 'sign__btn'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
