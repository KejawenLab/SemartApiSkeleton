<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Media\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface MediaRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByFilename(string $fileName, string $folder = null): ?MediaInterface;
}
