<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenLoyalty\Component\Network\Domain\Command\CreateNetwork;
use OpenLoyalty\Component\Network\Domain\NetworkId;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

/**
 * Class LoadNetworkData.
 */
class LoadNetworkData extends ContainerAwareFixture implements OrderedFixtureInterface
{
    const NETWORK_ID = '00000000-0000-474c-1111-b1dd880c07e2';
    const NETWORK2_ID = '00000000-0000-474c-1111-b1dd880c07e3';

    public function load(ObjectManager $manager)
    {
        $commandBus = $this->container->get('broadway.command_handling.command_bus');
        $commandBus->dispatch(
            new CreateNetwork(new NetworkId(static::NETWORK_ID), $this->getNetworkData())
        );

        $networkData = $this->getNetworkData();
        $networkData['name'] = 'Italian1';
        $networkData['location']['city'] = 'Vancouver';
        $networkData['identifier'] = 'network2';
        $networkData['description'] = 'To promote italian food and living';

        $commandBus->dispatch(
            new CreateNetwork(new NetworkId(static::NETWORK2_ID), $networkData)
        );
    }

    protected function getNetworkData()
    {
        return [
            'name' => 'Healthy1',
            'identifier' => 'network1',
            'description' => 'A Healthy Network to reward and motivate healthy habits',
            'location' => $this->getLocationData(),
        ];
    }

    protected function getLocationData()
    {
        return [
            'city' => 'Vancouver',
            'country' => 'CA',
            'postal' => 'V6Y 4L9',
            'region' => 'British Columbia',
            'lat1' => '51.1170364',
            'long1' => '17.0203959',
            'lat2' => '51.1170364',
            'long2' => '17.0203959',
        ];
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}
