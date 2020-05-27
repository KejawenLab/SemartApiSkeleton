<?php

declare(strict_types=1);

namespace App\Pagination;

use App\Setting\SettingService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Paginator
{
    private $pageField;

    private $perPageField;

    private $perPageDefault;

    private $sortField;

    private $sortDirectionField;

    public function __construct(SettingService $settingService)
    {
        $this->pageField = $settingService->getSetting('PAGE_FIELD')->getValue();
        $this->perPageField = $settingService->getSetting('PER_PAGE_FIELD')->getValue();
        $this->perPageDefault = (int) $settingService->getSetting('PER_PAGE')->getValue();
        $this->sortField = $settingService->getSetting('SORT_FIELD')->getValue();
        $this->sortDirectionField = $settingService->getSetting('SORT_DIRECTION_FIELD')->getValue();
    }

    public function createFromRequest(Request $request, string $sortField = 'id', string $sortDirection = 'ASC'): Pagination
    {
        return new Pagination(
            $request->get($this->pageField, 1),
            $request->get($this->perPageField, $this->perPageDefault),
            $request->get($this->sortField, $sortField),
            $request->get($this->sortDirectionField, $sortDirection)
        );
    }
}
