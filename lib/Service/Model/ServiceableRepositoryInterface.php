<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Service\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface ServiceableRepositoryInterface
{
    public function findAll(): iterable;

    public function countRecords(): int;

    public function persist(object $object): void;

    public function commit(): void;

    public function remove(object $object): void;
}
