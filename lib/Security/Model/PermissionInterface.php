<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface PermissionInterface
{
    public function getGroup(): ?GroupInterface;

    public function setGroup(GroupInterface $group): self;

    public function getMenu(): ?MenuInterface;

    public function setMenu(MenuInterface $menu): self;

    public function isAddable(): bool;

    public function isEditable(): bool;

    public function isViewable(): bool;

    public function isDeletable(): bool;
}
