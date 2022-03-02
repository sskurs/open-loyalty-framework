<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class LocationFormType.
 */
class LocationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('postal', TextType::class, [
            'label' => 'Post code',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ])->add('city', TextType::class, [
            'label' => 'City',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ])->add('region', TextType::class, [
            'label' => 'Region',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ])->add('country', CountryType::class, [
            'label' => 'Country',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ])->add('lat1', TextType::class, [
            'required' => false,
        ])->add('long1', TextType::class, [
            'required' => false,
        ])->add('lat2', TextType::class, [
            'required' => false,
        ])->add('long2', TextType::class, [
            'required' => false,
        ]);
    }
}
