<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface ApiClientRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByApiKey(string $apiKey): ?ApiClientInterface;
}
