<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface SettingRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByParameter(string $parameter): ?SettingInterface;
}
