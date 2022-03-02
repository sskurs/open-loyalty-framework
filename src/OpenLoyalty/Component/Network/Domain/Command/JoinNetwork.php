<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Network\Domain\Command;

use OpenLoyalty\Component\Network\Domain\NetworkId;
use OpenLoyalty\Component\Seller\Domain\SellerId;

/**
 * Class JoinNetwork.
 */
class JoinNetwork extends NetworkCommand
{
    protected $networkData;

    /**
     * RegisterCustomerCommand.
     *
     * @param SellerId $sellerId
     * @param $sellerData
     */
    public function __construct(NetworkId $networkId, $sellerData)
    {
        parent::__construct($networkId);

        $this->sellerData = $sellerData;
    }

    /**
     * @return mixed
     */
    public function getSellerData()
    {
        return $this->sellerData;
    }
}
