<?php

namespace App\Form;

use App\Entity\Database;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CrudFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'attr' => array('class' => 'form-control'),
            ])
            ->add('age', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                ],
                'expanded' => true, 
                'multiple' => false, 
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'data' => 'Male'
            ])

            ->add('mobile',NumberType::class,[
                'attr' => array('class' => 'form-control'),
            ])

            ->add('cource', ChoiceType::class, 
            [
                'label' => 'Select Course',
                'choices' => [
                    'PHP' => 'PHP',
                    'Laravel' => 'Laravel',
                    'Angular' => 'Angular',
                    'Symfony' => 'Symfony',
                ],
                'expanded' => true,
                'multiple' => true,
                'attr' => [
                    'class' => 'input-group form-check-input',
                ],
                'data' => ['PHP'],
            ])

            ->add('city', ChoiceType::class, [
                'required' => true,
                'label' => 'Select City',
                'attr' => array('class' => 'form-select'),
                'choices'  => [
                    'Select' => null,
                    'Ujjain' => 'Ujjain',
                    'Indore' => 'Indore',
                ],
                'choice_attr' => [
                    'Select' => ['disabled' => 'disabled']
                ]
            ])

            ->add('image',FileType::class,
            array('data_class' => null,'required' => true),
            [
                'attr' => array('class' => 'form-control'),
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Database::class,
        ]);
    }


}



