<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface MenuInterface extends PermissionableInterface
{
    public function getId(): ?string;

    public function getParent(): ?self;

    public function getCode(): ?string;

    public function getName(): ?string;

    public function getSortOrder(): ?int;

    public function getRouteName(): ?string;

    public function setUrlPath(string $urlPath): self;

    public function isShowable(): ?bool;
}
