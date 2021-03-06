<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Service\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableServiceInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface ServiceInterface extends PaginatableServiceInterface
{
    public function all(): array;

    public function get(string $id);

    public function save(object $object): void;

    public function remove(object $object): void;
}
