<?php

declare(strict_types=1);

namespace App\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PermissionInterface
{
    public function getGroup(): ?GroupInterface;

    public function getMenu(): ?MenuInterface;

    public function isAddable(): bool;

    public function isEditable(): bool;

    public function isViewable(): bool;

    public function isDeletable(): bool;
}
