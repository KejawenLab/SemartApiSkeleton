<?php

declare(strict_types=1);

namespace App\Repository;

use App\Pagination\Model\PaginatableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements PaginatableRepositoryInterface
{
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
