<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Network\Domain\Command;

use OpenLoyalty\Component\Network\Domain\NetworkId;

/**
 * Class NetworkCommand.
 */
abstract class NetworkCommand
{
    /**
     * @var NetworkId
     */
    protected $networkId;

    /**
     * NetworkCommand constructor.
     *
     * @param NetworkId $networkId
     */
    public function __construct(NetworkId $networkId)
    {
        $this->networkId = $networkId;
    }

    /**
     * @return NetworkId
     */
    public function getNetworkId()
    {
        return $this->networkId;
    }
}
