<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements PaginatableRepositoryInterface
{
    protected const MICRO_CACHE = 3;

    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function persist(object $object): void
    {
        $this->_em->persist($object);
        $this->_em->flush();
    }

    public function remove(object $object): void
    {
        $this->_em->remove($object);
        $this->_em->flush();
    }

    public function queryBuilder(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
