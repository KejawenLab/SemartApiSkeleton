<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements PaginatableRepositoryInterface
{
    protected const MICRO_CACHE = 3;

    /**
     * @return mixed|null
     *
     * @throws NonUniqueResultException
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.id', $queryBuilder->expr()->literal($id)));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, $id);

        return $query->getOneOrNullResult();
    }

    public function findAll(): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        return $query->getResult();
    }

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
