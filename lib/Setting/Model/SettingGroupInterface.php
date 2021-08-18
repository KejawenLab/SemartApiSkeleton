<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface SettingGroupInterface
{
    public function getKey(): string;

    public function getValue(): string;
}
