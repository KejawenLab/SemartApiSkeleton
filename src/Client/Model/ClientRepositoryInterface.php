<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Client\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface ClientRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByApiKey(string $apiKey): ?ClientInterface;
}
