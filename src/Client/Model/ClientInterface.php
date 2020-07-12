<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Client\Model;

use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface ClientInterface extends AuthInterface
{
    public function getUser(): ?UserInterface;

    public function getApiKey(): ?string;

    public function getSecretKey(): ?string;
}
