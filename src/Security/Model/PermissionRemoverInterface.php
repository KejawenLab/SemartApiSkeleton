<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PermissionRemoverInterface
{
    public function remove(PermissionableInterface $object): void;

    public function setClass(string $class): void;

    public function support(PermissionableInterface $object): bool;
}
