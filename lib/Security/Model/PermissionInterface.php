<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface PermissionInterface extends EntityInterface
{
    public function getGroup(): ?GroupInterface;

    public function setGroup(GroupInterface $group): void;

    public function getMenu(): ?MenuInterface;

    public function setMenu(MenuInterface $menu): void;

    public function isAddable(): bool;

    public function isEditable(): bool;

    public function isViewable(): bool;

    public function isDeletable(): bool;
}
