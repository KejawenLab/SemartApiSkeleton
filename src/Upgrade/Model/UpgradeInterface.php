<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Upgrade\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface UpgradeInterface
{
    public function upgrade(): void;

    public function support(): bool;
}
