<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface SettingInterface extends EntityInterface
{
    public function getGroup(): ?string;

    public function getParameter(): ?string;

    public function getValue(): ?string;

    public function isPublic(): bool;

    public function isReserved(): bool;
}
