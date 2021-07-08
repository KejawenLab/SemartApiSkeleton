<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface MenuInterface extends PermissionableInterface, EntityInterface
{
    public function getParent(): ?self;

    public function getCode(): ?string;

    public function getName(): ?string;

    public function getSortOrder(): ?int;

    public function getRouteName(): ?string;

    public function getApiPath(): ?string;

    public function setApiPath(string $apiPath): void;

    public function getAdminPath(): ?string;

    public function setAdminPath(string $adminPath): void;

    public function getExtra(): ?string;

    public function isShowable(): ?bool;
}
