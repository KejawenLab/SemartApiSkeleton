<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Setting\Model;

use KejawenLab\Semart\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface SettingRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByParameter(string $parameter): ?SettingInterface;
}
