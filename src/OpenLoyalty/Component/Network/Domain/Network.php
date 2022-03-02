<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Network\Domain;

use OpenLoyalty\Component\Network\Domain\Model\Location;
use Assert\Assertion as Assert;

/**
 * Class Network.
 */
class Network
{
    /**
     * @var NetworkId
     */
    protected $networkId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var Location
     */
    protected $location;

    public function __construct(NetworkId $networkId, array $data = [])
    {
        $this->networkId = $networkId;
        $this->setFromArray($data);
    }

    public function setFromArray(array $data = [])
    {
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['identifier'])) {
            $this->identifier = $data['identifier'];
        }
        if (isset($data['location'])) {
            $this->location = Location::deserialize($data['location']);
        }
    }

    public static function validateRequiredData(array $data = [])
    {
        Assert::keyIsset($data, 'name');
        Assert::keyIsset($data, 'identifier');
        Assert::keyIsset($data, 'location');
        Location::validateRequiredData($data['location']);
    }

    /**
     * @return NetworkId
     */
    public function getNetworkId()
    {
        return $this->networkId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
}
