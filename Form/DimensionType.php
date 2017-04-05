<?php

namespace Thunken\CroploadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DimensionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('cropWidth', HiddenType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'crop-width'
                ]
            ])
            ->add('cropHeight', HiddenType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'crop-height'
                ]
            ])
            ->add('cropOffsetX', HiddenType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'crop-offset-x'
                ]
            ])
            ->add('cropOffsetY', HiddenType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'crop-offset-y'
                ]
            ])
        ;
    }

    public function getBlockPrefix() {
        return 'thunken_cropload_dimension';
    }
}