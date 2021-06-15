<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface PasswordHistoryRepositoryInterface extends ServiceableRepositoryInterface
{
    public function findPasswords(UserInterface $user): array;
}
