<?php

namespace Thunken\CroploadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ImageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('upload_endpoint'); // Requires that upload_endpoint be set by the caller.
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}