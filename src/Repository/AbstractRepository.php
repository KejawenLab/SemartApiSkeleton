<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Alpabit\ApiSkeleton\Entity\Event\PersistEvent;
use Alpabit\ApiSkeleton\Entity\Event\RemoveEvent;
use Alpabit\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements PaginatableRepositoryInterface
{
    protected const MICRO_CACHE = 3;

    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher, ManagerRegistry $registry, $entityClass)
    {
        $this->eventDispatcher = $eventDispatcher;

        parent::__construct($registry, $entityClass);
    }

    public function persist(object $object): void
    {
        $this->eventDispatcher->dispatch(new PersistEvent($this->_em, $object));

        $this->_em->persist($object);
        $this->_em->flush();
    }

    public function remove(object $object): void
    {
        $this->eventDispatcher->dispatch(new RemoveEvent($this->_em, $object));

        $this->_em->remove($object);
        $this->_em->flush();
    }

    public function queryBuilder(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
