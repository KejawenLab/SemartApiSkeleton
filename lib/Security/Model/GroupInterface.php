<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface GroupInterface extends PermissionableInterface
{
    public function getCode(): ?string;

    public function getName(): ?string;
}
