<?php

declare(strict_types=1);

namespace App\Pagination;

use App\Pagination\Model\FilterInterface;
use App\Setting\SettingService;
use App\Util\Serializer;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Paginator
{
    private $serializer;

    private $pageField;

    private $perPageField;

    private $perPageDefault;

    private $sortField;

    private $sortDirectionField;

    private $cacheLifetime;

    /**
     * @var FilterInterface[]
     */
    private $filters;

    public function __construct(SettingService $settingService, Serializer $serializer, array $filters = [])
    {
        $this->pageField = $settingService->getSetting('PAGE_FIELD')->getValue();
        $this->perPageField = $settingService->getSetting('PER_PAGE_FIELD')->getValue();
        $this->perPageDefault = (int) $settingService->getSetting('PER_PAGE')->getValue();
        $this->sortField = $settingService->getSetting('SORT_FIELD')->getValue();
        $this->sortDirectionField = $settingService->getSetting('SORT_DIRECTION_FIELD')->getValue();
        $this->cacheLifetime = (int) $settingService->getSetting('CACHE_LIFETIME')->getValue();
        $this->serializer = $serializer;
        $this->filters = $filters;
    }

    public function paginate(QueryBuilder $queryBuilder, Request $request, string $class, string $sortField = 'id', string $sortDirection = 'ASC'): array
    {
        $pagination = $this->pagination($request, $sortField, $sortDirection);
        foreach ($this->filters as $filter) {
            if ($filter->support($class)) {
                $filter->apply($queryBuilder);
            }
        }

        return [
            'items' => $this->serializer->toArray($this->paging($queryBuilder, $pagination), ['groups' => 'read']),
            'page' => $pagination->getPage(),
            'per_page' => $pagination->getPerPage(),
            'total_page' => ceil($this->count($queryBuilder) / $pagination->getPerPage()),
            'total_item' => $this->count($queryBuilder),
        ];
    }

    private function paging(QueryBuilder $queryBuilder, Pagination $pagination): array
    {
        $queryBuilder->setMaxResults($pagination->getPerPage());
        $queryBuilder->setFirstResult(($pagination->getPage() - 1) * $pagination->getPerPage());

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($this->cacheLifetime, sprintf('%s:%s:%s', __CLASS__, __METHOD__, serialize($queryBuilder->getParameters())));

        return $query->getResult();
    }

    private function count(QueryBuilder $queryBuilder): int
    {
        $count = clone $queryBuilder;
        $count->select('COUNT(1) AS total');

        $query = $count->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($this->cacheLifetime, sprintf('%s:%s:%s', __CLASS__, __METHOD__, serialize($count->getParameters())));

        return (int) $query->getSingleScalarResult();
    }

    private function pagination(Request $request, string $sortField = 'id', string $sortDirection = 'ASC'): Pagination
    {
        return new Pagination(
            $request->get($this->pageField, 1),
            $request->get($this->perPageField, $this->perPageDefault),
            $request->get($this->sortField, $sortField),
            $request->get($this->sortDirectionField, $sortDirection)
        );
    }
}
