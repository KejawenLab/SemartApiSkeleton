<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface MenuInterface extends PermissionableInterface
{
    public function getParent(): ?self;

    public function getCode(): ?string;

    public function getName(): ?string;

    public function getSortOrder(): ?int;

    public function getRouteName(): ?string;

    public function getApiPath(): ?string;

    public function setApiPath(string $apiPath): self;

    public function getAdminPath(): ?string;

    public function setAdminPath(string $adminPath): self;

    public function getExtra(): ?string;

    public function isShowable(): ?bool;
}
