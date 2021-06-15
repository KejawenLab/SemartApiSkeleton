<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements PaginatableRepositoryInterface
{
    protected const MICRO_CACHE = 1;

    public function countRecords(): int
    {
        return $this->count([]);
    }

    /**
     * @throws ORMException
     */
    public function persist(object $object): void
    {
        $this->_em->persist($object);
    }

    /**
     * @throws ORMException
     */
    public function remove(object $object): void
    {
        $this->_em->remove($object);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function commit(): void
    {
        $this->_em->flush();
    }

    public function queryBuilder(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
