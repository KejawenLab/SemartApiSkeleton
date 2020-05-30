<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\Semart\ApiSkeleton\Entity\Event\PersistEvent;
use KejawenLab\Semart\ApiSkeleton\Entity\Event\RemoveEvent;
use KejawenLab\Semart\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements PaginatableRepositoryInterface
{
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
