<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Service\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface ServiceableRepositoryInterface
{
    public function persist(object $object): void;

    public function commit(): void;

    public function remove(object $object): void;
}
