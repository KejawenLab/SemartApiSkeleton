<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination\Model;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface PaginatableServiceInterface
{
    public function getQueryBuilder(): QueryBuilder;
}
