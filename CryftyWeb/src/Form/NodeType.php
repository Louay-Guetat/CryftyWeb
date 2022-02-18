<?php

namespace App\Form;

use App\Entity\Crypto\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nodeLabel',TextType::class,[
                'attr'=>['class'=>'form-control'],
                'label_attr'=>['class'=>'col-sm-2 col-sm-2 control-label'],
                'label'=>'Enter Node label'
            ])
            ->add('submit',SubmitType::class,[
                'attr'=>['class'=>'btn btn-info']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Node::class,
        ]);
    }
}
