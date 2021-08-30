<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Service;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Entity\Message\EntityPersisted;
use KejawenLab\ApiSkeleton\Entity\Message\EntityRemoved;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractService implements ServiceInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        protected ServiceableRepositoryInterface $repository,
        protected AliasHelper $aliasHelper,
    ) {
    }

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->repository->findAll();
    }

    public function total(): int
    {
        return $this->repository->countRecords();
    }

    public function get(string $id): ?object
    {
        if (!Uuid::isValid($id)) {
            return null;
        }

        return $this->repository->find($id);
    }

    public function save(EntityInterface $object): void
    {
        $this->repository->persist($object);
        $this->messageBus->dispatch(new EntityPersisted($object));
        $this->repository->commit();
    }

    public function remove(EntityInterface $object): void
    {
        $this->repository->remove($object);
        $this->messageBus->dispatch(new EntityRemoved($object));
        $this->repository->commit();
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->repository->queryBuilder($this->aliasHelper->findAlias('root'));
    }
}
