<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface GroupInterface extends PermissionableInterface, EntityInterface
{
    const SUPER_ADMIN_ID = '1dcc811a-c791-493d-aec8-a9608f0f0ee5';

    const SUPER_ADMIN_CODE = 'SPRADM';

    public function getCode(): ?string;

    public function getName(): ?string;
}
