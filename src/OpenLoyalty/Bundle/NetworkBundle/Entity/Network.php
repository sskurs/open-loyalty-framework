<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenLoyalty\Component\Network\Domain\NetworkId;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ol__network")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @JMS\ExclusionPolicy("all")
 * @UniqueEntity(
 *  fields={"username"},
 *  message="User with this username already exists"
 * )
 */
class Network
{
    /**
     * @ORM\Column(type="string")
     * @ORM\Id
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Expose()
     */
    protected $description;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false, name="allow_point_transfer", options={"default":false})
     */
    protected $allowPointTransfer = false;

    /**
     * @ORM\ManyToMany(targetEntity="Network")
     * @ORM\JoinTable(name="ol__sellers_networks")
     * @JMS\Expose()
     */
    protected $networks;

    /**
     * Network constructor.
     *
     * @param SellerId $id
     */
    public function __construct(SellerId $id)
    {
        parent::__construct((string) $id);
        $this->networks = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isAllowPointTransfer(): bool
    {
        return $this->allowPointTransfer;
    }

    /**
     * @param bool $allowPointTransfer
     */
    public function setAllowPointTransfer(bool $allowPointTransfer): void
    {
        $this->allowPointTransfer = $allowPointTransfer;
    }

    /**
     * @return mixed
     */
    public function getNetworks()
    {
        if (!is_array($this->networks)) {
            $networks = $this->networks->toArray();
        } else {
            $networks = $this->networks;
        }

        return $networks;
    }

    /**
     * @param mixed $networks
     */
    public function setNetworks($networks)
    {
        $this->networks = $networks;
    }    
}
