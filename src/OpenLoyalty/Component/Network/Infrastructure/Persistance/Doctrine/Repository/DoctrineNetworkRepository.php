<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Network\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use OpenLoyalty\Component\Core\Infrastructure\Persistence\Doctrine\SortByFilter;
use OpenLoyalty\Component\Core\Infrastructure\Persistence\Doctrine\SortFilter;
use OpenLoyalty\Component\Network\Domain\Network;
use OpenLoyalty\Component\Network\Domain\NetworkId;
use OpenLoyalty\Component\Network\Domain\NetworkRepository;

/**
 * Class DoctrineNetworkRepository.
 */
class DoctrineNetworkRepository extends EntityRepository implements NetworkRepository
{
    use SortFilter, SortByFilter;

    /**
     * {@inheritdoc}
     */
    public function findAll($returnQueryBuilder = false)
    {
        if ($returnQueryBuilder) {
            return $this->createQueryBuilder('e');
        }

        return parent::findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function byId(NetworkId $networkId)
    {
        return parent::find($networkId);
    }

    /**
     * {@inheritdoc}
     */
    public function save(Network $network)
    {
        $this->getEntityManager()->persist($network);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Network $network)
    {
        $this->getEntityManager()->remove($network);
    }

    /**
     * {@inheritdoc}
     */
    public function oneByIdentifier($identifier)
    {
        return $this->findOneBy(['identifier' => $identifier]);
    }

    /**
     * {@inheritdoc}
     */
    public function findAllPaginated($page = 1, $perPage = 10, $sortField = null, $direction = 'ASC')
    {
        $qb = $this->createQueryBuilder('l');
        if ($page < 1) {
            $page = 1;
        }

        if ($sortField) {
            $qb->orderBy(
                'l.'.$this->validateSort($sortField),
                $this->validateSortBy($direction)
            );
        }
        if ($perPage) {
            $qb->setMaxResults($perPage);
            $qb->setFirstResult(($page - 1) * $perPage);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function countTotal()
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('count(l.networkId)');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
