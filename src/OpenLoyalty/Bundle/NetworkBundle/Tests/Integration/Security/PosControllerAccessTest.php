<?php

namespace OpenLoyalty\Bundle\NetworkBundle\Tests\Integration\Security;

use OpenLoyalty\Bundle\CoreBundle\Tests\Integration\BaseAccessControlTest;
use OpenLoyalty\Bundle\NetworkBundle\DataFixtures\ORM\LoadNetworkData;

/**
 * Class NetworkControllerAccessTest.
 */
class NetworkControllerAccessTest extends BaseAccessControlTest
{
    /**
     * @test
     */
    public function only_admin_and_seller_should_have_access_to_all_network_list()
    {
        $clients = [
            ['client' => $this->getCustomerClient(), 'status' => 403, 'name' => 'customer'],
            ['client' => $this->getSellerClient(), 'not_status' => 403, 'name' => 'seller'],
            ['client' => $this->getAdminClient(), 'not_status' => 403, 'name' => 'admin'],
        ];

        $this->checkClients($clients, '/api/network');
    }

    /**
     * @test
     */
    public function only_admin_can_edit_network()
    {
        $clients = [
            ['client' => $this->getCustomerClient(), 'status' => 403, 'name' => 'customer'],
            ['client' => $this->getSellerClient(), 'status' => 403, 'name' => 'seller'],
            ['client' => $this->getAdminClient(), 'not_status' => 403, 'name' => 'admin'],
        ];

        $this->checkClients($clients, '/api/network/'.LoadNetworkData::NETWORK_ID, [], 'PUT');
    }

    /**
     * @test
     */
    public function only_admin_can_create_network()
    {
        $clients = [
            ['client' => $this->getCustomerClient(), 'status' => 403, 'name' => 'customer'],
            ['client' => $this->getSellerClient(), 'status' => 403, 'name' => 'seller'],
            ['client' => $this->getAdminClient(), 'not_status' => 403, 'name' => 'admin'],
        ];

        $this->checkClients($clients, '/api/network', [], 'POST');
    }

    /**
     * @test
     */
    public function only_admin_and_seller_can_view_network()
    {
        $clients = [
            ['client' => $this->getCustomerClient(), 'status' => 403, 'name' => 'customer'],
            ['client' => $this->getSellerClient(), 'not_status' => 403, 'name' => 'seller'],
            ['client' => $this->getAdminClient(), 'not_status' => 403, 'name' => 'admin'],
        ];

        $this->checkClients($clients, '/api/network/'.LoadNetworkData::NETWORK_ID);
    }
}
