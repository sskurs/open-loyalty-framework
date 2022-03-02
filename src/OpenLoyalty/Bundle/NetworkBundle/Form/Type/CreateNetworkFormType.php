<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Form\Type;

use OpenLoyalty\Bundle\NetworkBundle\Form\DataTransformer\LocationDataTransformer;
use OpenLoyalty\Bundle\NetworkBundle\Model\Network;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class CreateNetworkFormType.
 */
class CreateNetworkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'required' => true,
            'constraints' => [new NotBlank()],
        ]);
        $builder->add('identifier', TextType::class, [
            'required' => true,
            'constraints' => [new NotBlank()],
        ]);
        $builder->add('description', TextType::class, [
            'required' => false,
        ]);
        $location = $builder->create('location', LocationFormType::class, [
            'constraints' => [new NotBlank(), new Valid()],
        ]);
        $location->addModelTransformer(new LocationDataTransformer());
        $builder->add($location);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Network::class,
        ]);
    }
}
