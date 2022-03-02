<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Network\Domain;

use OpenLoyalty\Component\Core\Domain\Model\Identifier;
use Assert\Assertion as Assert;

/**
 * Class NetworkId.
 */
class NetworkId implements Identifier
{
    /**
     * @var string
     */
    protected $networkId;

    /**
     * PosId constructor.
     *
     * @param string $networkId
     */
    public function __construct($networkId)
    {
        Assert::string($networkId);
        Assert::uuid($networkId);

        $this->networkId = $networkId;
    }

    public function __toString()
    {
        return $this->networkId;
    }
}
