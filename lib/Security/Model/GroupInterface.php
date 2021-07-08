<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface GroupInterface extends PermissionableInterface, EntityInterface
{
    public function getCode(): ?string;

    public function getName(): ?string;
}
