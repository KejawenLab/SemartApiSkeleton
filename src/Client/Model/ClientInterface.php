<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Client\Model;

use Alpabit\ApiSkeleton\Security\Model\AuthInterface;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface ClientInterface extends AuthInterface
{
    public function getUser(): ?UserInterface;

    public function getApiKey(): ?string;

    public function getSecretKey(): ?string;
}
