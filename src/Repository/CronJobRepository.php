<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Repository;

use Cron\CronBundle\Entity\CronJob;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class CronJobRepository implements PaginatableRepositoryInterface
{
    private $manager;

    private $repository;

    private $aliasHelper;

    public function __construct(EntityManagerInterface $manager, AliasHelper $aliasHelper)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(CronJob::class);
        $this->aliasHelper = $aliasHelper;
    }

    public function queryBuilder(string $alias): QueryBuilder
    {
        return $this->repository->createQueryBuilder($this->aliasHelper->findAlias('root'));
    }

    public function persist(object $object): void
    {
        $this->manager->persist($object);
        $this->manager->flush();
    }

    public function remove(object $object): void
    {
        $this->manager->remove($object);
        $this->manager->flush();
    }

    public function find(string $id): ?CronJob
    {
        return $this->repository->find($id);
    }
}
