<?php

namespace App\Form;

use App\Entity\BasicDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BasicDetailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('state',TextType::class,[
                'attr' => array('class' => 'form-control'),
            ])
            ->add('dist',TextType::class,[
                'attr' => array('class' => 'form-control'),
            ])
            ->add('zip',TextType::class,[
                'attr' => array('class' => 'form-control'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BasicDetail::class,
        ]);
    }

}
?>