<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Service;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractService implements ServiceInterface
{
    protected $repository;

    protected $aliasHelper;

    public function __construct(ServiceableRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        $this->repository = $repository;
        $this->aliasHelper = $aliasHelper;
    }

    public function get(string $id)
    {
        return $this->repository->find($id);
    }

    public function save(object $object): void
    {
        $this->repository->persist($object);
    }

    public function remove(object $object): void
    {
        $this->repository->remove($object);
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->repository->queryBuilder($this->aliasHelper->findAlias('root'));
    }
}
