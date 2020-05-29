<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Service\Model;

use KejawenLab\Semart\ApiSkeleton\Pagination\Model\PaginatableServiceInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface ServiceInterface extends PaginatableServiceInterface
{
    public function get(string $id, bool $toArray = false);

    public function save(object $object): void;

    public function remove(object $object): void;
}
