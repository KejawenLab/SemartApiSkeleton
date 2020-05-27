<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Model\ServiceableRepositoryInterface;
use App\Service\Model\ServiceInterface;
use App\Util\Serializer;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractService implements ServiceInterface
{
    protected $repository;

    protected $serializer;

    public function __construct(ServiceableRepositoryInterface $repository, Serializer $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    public function get(string $id, bool $toArray = false)
    {
        if ($toArray) {
            return $this->serializer->toArray($this->repository->find($id), ['groups' => 'read']);
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

    public function getQueryBuilder(string $alias): QueryBuilder
    {
        return $this->repository->queryBuilder($alias);
    }
}
