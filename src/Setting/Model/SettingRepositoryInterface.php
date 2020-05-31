<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Setting\Model;

use Alpabit\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface SettingRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByParameter(string $parameter): ?SettingInterface;
}
