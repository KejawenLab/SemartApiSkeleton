<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface PasswordHistoryInterface
{
    public function getSource(): ?string;

    public function getIdentifier(): ?string;

    public function getPassword(): ?string;
}
