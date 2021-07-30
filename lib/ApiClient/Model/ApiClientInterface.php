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
    public function getUser(): ?UserInterface;

    public function getName(): ?string;

    public function getApiKey(): ?string;

    public function getSecretKey(): ?string;
}
