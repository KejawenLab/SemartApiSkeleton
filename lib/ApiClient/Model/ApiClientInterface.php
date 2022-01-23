<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface ApiClientInterface extends AuthInterface, EntityInterface
{
    public const DEVICE_ID = 'API_CLIENT_DEVICE_ID';

    public function getUser(): ?UserInterface;

    public function setUser(UserInterface $user): void;

    public function getName(): ?string;

    public function getApiKey(): ?string;

    public function getSecretKey(): ?string;
}
