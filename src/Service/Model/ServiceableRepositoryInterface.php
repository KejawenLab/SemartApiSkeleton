<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Service\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface ServiceableRepositoryInterface
{
    public function persist(object $object): void;

    public function remove(object $object): void;
}
