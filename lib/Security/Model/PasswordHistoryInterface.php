<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface PasswordHistoryInterface
{
    public function getSource(): ?string;

    public function getIdentifier(): ?string;

    public function getPassword(): ?string;
}
