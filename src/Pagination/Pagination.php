<?php

declare(strict_types=1);

namespace App\Pagination;

use App\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Pagination
{
    private $page;

    private $perPage;

    private $sortField;

    private $sortDirection;

    public function __construct(int $page = 1, int $perPage = 17, ?string $sortField = null, ?string $sortDirection = 'ASC')
    {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->sortField = $sortField;
        $this->sortDirection = StringUtil::uppercase($sortDirection);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getSortField(): ?string
    {
        return $this->sortField;
    }

    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }
}
