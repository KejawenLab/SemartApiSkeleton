<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Service;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\Semart\ApiSkeleton\Util\Serializer;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractService implements ServiceInterface
{
    protected $repository;

    protected $serializer;

    protected $aliasHelper;

    public function __construct(ServiceableRepositoryInterface $repository, Serializer $serializer, AliasHelper $aliasHelper)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->aliasHelper = $aliasHelper;
    }

    public function get(string $id, bool $toArray = false)
    {
        if ($toArray) {
            return $this->serializer->toArray($this->repository->find($id));
        }

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
