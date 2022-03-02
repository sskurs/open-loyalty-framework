<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Model;

use OpenLoyalty\Component\Network\Domain\Network as BaseNetwork;

/**
 * Class Pos.
 */
class Network extends BaseNetwork
{
    public function __construct()
    {
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'identifier' => $this->getIdentifier(),
            'location' => $this->getLocation() ? $this->getLocation()->serialize() : null,
        ];
    }
}
