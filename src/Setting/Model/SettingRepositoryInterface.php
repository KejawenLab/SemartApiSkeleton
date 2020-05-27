<?php

declare(strict_types=1);

namespace App\Setting\Model;

use App\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface SettingRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByParameter(string $parameter): ?SettingInterface;

    public function persist(SettingInterface $setting): void;

    public function remove(SettingInterface $setting): void;
}
