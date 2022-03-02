<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Class EditNetworkFormType.
 */
class EditNetworkFormType extends AbstractType
{
    public function getParent()
    {
        return CreateNetworkFormType::class;
    }
}
