<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Pagination;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Pagination
{
    private int $page;

    private int $perPage;

    public function __construct(int $page = 1, int $perPage = 17)
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
