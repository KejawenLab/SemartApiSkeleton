<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface AuthInterface
{
    public function getGroup(): ?GroupInterface;

    public function getIdentity(): ?string;

    public function getCredential(): ?string;

    public function isEncoded(): bool;
}
