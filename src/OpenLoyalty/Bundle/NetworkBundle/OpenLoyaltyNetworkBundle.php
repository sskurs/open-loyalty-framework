<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenLoyaltyNetworkBundle.
 */
class OpenLoyaltyNetworkBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass($this->buildMappingCompilerPass());
    }

    /**
     * @return DoctrineOrmMappingsPass
     */
    public function buildMappingCompilerPass()
    {
        return DoctrineOrmMappingsPass::createYamlMappingDriver(
            [__DIR__.'/../../Component/Network/Infrastructure/Persistence/Doctrine/ORM' => 'OpenLoyalty\Component\Network\Domain'],
            [],
            false,
            ['OpenLoyaltyNetwork' => 'OpenLoyalty\Component\Network\Domain']
        );
    }
}
