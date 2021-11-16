<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface ApiClientRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByApiKey(string $apiKey): ?ApiClientInterface;

    public function findByIdAndUser(string $id, UserInterface $user): ?ApiClientInterface;

    public function countByUser(UserInterface $user): int;
}
