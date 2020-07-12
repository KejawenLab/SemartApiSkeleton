<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface SettingInterface
{
    public function getParameter(): ?string;

    public function getValue(): ?string;

    public function isPublic(): bool;
}
