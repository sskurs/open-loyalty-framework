<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Network\Domain\Command;

use Broadway\CommandHandling\SimpleCommandHandler;
use OpenLoyalty\Component\Network\Domain\NetworkId;
use OpenLoyalty\Component\Seller\Domain\Seller;
use OpenLoyalty\Component\Network\Domain\NetworkRepository;

/**
 * Class NetworkCommandHandler.
 */
class NetworkCommandHandler extends SimpleCommandHandler
{
    /**
     * @var NetworkRepository
     */
    private $repository;

    /**
     * @var SellerUniqueValidator
     */
    private $uniqueValidator;

    /**
     * SellerCommandHandler constructor.
     *
     * @param SellerRepository      $repository
     * @param SellerUniqueValidator $uniqueValidator
     */
    public function __construct(SellerRepository $repository, SellerUniqueValidator $uniqueValidator)
    {
        $this->repository = $repository;
        $this->uniqueValidator = $uniqueValidator;
    }

    public function handleJoinNetwork(JoinNetwork $command)
    {
        $sellerData = $command->getSellerData();
        if (isset($sellerData['networkId']) && !$sellerData['networkId'] instanceof NetworkId) {
            $sellerData['networkId'] = new NetworkId($sellerData['networkId']);
        }
        /** @var Seller $seller */
        $seller = $this->repository->load($command->getSellerId());
        $seller->update($sellerData);
        $this->repository->save($seller);
    }
}
