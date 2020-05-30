<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Service\Model;

use KejawenLab\Semart\ApiSkeleton\Pagination\Model\PaginatableServiceInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface ServiceInterface extends PaginatableServiceInterface
{
    public function get(string $id);

    public function save(object $object): void;

    public function remove(object $object): void;
}
