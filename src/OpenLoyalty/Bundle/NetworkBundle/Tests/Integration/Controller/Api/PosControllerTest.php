<?php

namespace OpenLoyalty\Bundle\NetworkBundle\Tests\Integration\Controller\Api;

use OpenLoyalty\Bundle\CoreBundle\Tests\Integration\BaseApiTest;
use OpenLoyalty\Bundle\NetworkBundle\DataFixtures\ORM\LoadNetworkData;
use OpenLoyalty\Component\Network\Domain\Network;
use OpenLoyalty\Component\Network\Domain\NetworkId;
use OpenLoyalty\Component\Network\Domain\NetworkRepository;

/**
 * Class NetworkControllerTest.
 */
class NetworkControllerTest extends BaseApiTest
{
    /**
     * @var NetworkRepository
     */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();

        static::bootKernel();
        $this->repository = static::$kernel->getContainer()->get('oloy.network.repository');
    }

    /**
     * @test
     */
    public function it_creates_network()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/network',
            [
                'network' => [
                    'name' => 'new network in wroclaw',
                    'identifier' => 'network3',
                    'location' => $this->getLocationData(),
                ],
            ]
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode(), 'Response should have status 200');
        $this->assertArrayHasKey('networkId', $data);
        $network = $this->repository->byId(new NetworkId($data['networkId']));
        $this->assertInstanceOf(Network::class, $network);
        $this->assertEquals('new network in wroclaw', $network->getName());
    }

    /**
     * @test
     */
    public function it_updates_network()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/network/'.LoadNetworkData::NETWORK_ID,
            [
                'network' => [
                    'name' => 'updated name',
                    'identifier' => 'updated identifier',
                    'location' => $this->getLocationData(),
                ],
            ]
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode(), 'Response should have status 200');
        $this->assertArrayHasKey('networkId', $data);
        /** @var Network $network */
        $network = $this->repository->byId(new NetworkId(LoadNetworkData::NETWORK_ID));
        $this->assertInstanceOf(Network::class, $network);
        $this->assertEquals('updated name', $network->getName());
        $this->assertEquals('updated identifier', $network->getIdentifier());
    }

    /**
     * @test
     */
    public function it_returns_network()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/network/'.LoadNetworkData::Network_ID
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode(), 'Response should have status 200');
        $this->assertArrayHasKey('name', $data);
    }

    /**
     * @test
     */
    public function it_returns_network_list()
    {
        $client = $this->createAuthenticatedClient();
        $client->insulate();
        $client->request(
            'GET',
            '/api/network'
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode(), 'Response should have status 200');
        $this->assertArrayHasKey('network', $data);
        $this->assertTrue(count($data['network']) > 0, 'There should be at least one network');
    }

    protected function getLocationData()
    {
        return [
            'city' => 'Richmond',
            'country' => 'CA',
            'postal' => 'V6Y 4L9',
            'province' => 'British Columbia',
            'lat1' => '51.1170364',
            'long1' => '17.0203959',
            'lat2' => '51.1170364',
            'long2' => '17.0203959',
        ];
    }
}
