<?php

namespace App\Form;

use App\Entity\Crypto\Node;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                'label_attr'=>['class'=>'col-sm-2 control-label'],
                'label'=>'Enter Node label'
            ])
            ->add('coinCode',TextType::class,[
                'attr' => ['class'=>'form-control'],
                'label_attr' => ['class'=>'col-sm-2 control-label'],
                'label' => 'Enter the code of your Node ! (Exp: BTC)'
            ])
            ->add('nodeReward',NumberType::class,[
                'attr' => ['class'=>'form-control'],
                'label_attr' => ['class'=>'col-sm-2 control-label'],
                'label' => 'Enter node\'s reward'
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
