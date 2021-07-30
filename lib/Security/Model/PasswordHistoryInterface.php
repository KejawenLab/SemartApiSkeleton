<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface PasswordHistoryInterface extends EntityInterface
{
    public function getSource(): ?string;

    public function getIdentifier(): ?string;

    public function getPassword(): ?string;
}
