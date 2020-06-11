<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Pagination;

use Alpabit\ApiSkeleton\Pagination\Model\QueryExtensionInterface;
use Alpabit\ApiSkeleton\Setting\SettingService;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Paginator
{
    private ?string $pageField;

    private ?string $perPageField;

    private int $perPageDefault;

    private int $cacheLifetime;

    /**
     * @var QueryExtensionInterface[]
     */
    private iterable $queryExtension;

    public function __construct(SettingService $settingService, iterable $queryExtension)
    {
        $this->pageField = $settingService->getSetting('PAGE_FIELD')->getValue();
        $this->perPageField = $settingService->getSetting('PER_PAGE_FIELD')->getValue();
        $this->perPageDefault = (int) $settingService->getSetting('PER_PAGE')->getValue();
        $this->cacheLifetime = (int) $settingService->getSetting('CACHE_LIFETIME')->getValue();
        $this->queryExtension = $queryExtension;
    }

    public function paginate(QueryBuilder $queryBuilder, Request $request, string $class): array
    {
        $pagination = $this->pagination($request);
        foreach ($this->queryExtension as $extension) {
            if ($extension->support($class)) {
                $extension->apply($queryBuilder, $request);
            }
        }

        return [
            'page' => $pagination->getPage(),
            'per_page' => $pagination->getPerPage(),
            'total_page' => ceil($this->count($queryBuilder) / $pagination->getPerPage()),
            'total_item' => $this->count($queryBuilder),
            'items' => $this->paging($queryBuilder, $pagination),
        ];
    }

    private function paging(QueryBuilder $queryBuilder, Pagination $pagination): array
    {
        $queryBuilder->setMaxResults($pagination->getPerPage());
        $queryBuilder->setFirstResult(($pagination->getPage() - 1) * $pagination->getPerPage());

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($this->cacheLifetime, sprintf('%s:%s:%s:%s', __CLASS__, __METHOD__, $pagination->getPage(), $pagination->getPerPage()));

        return $query->getResult();
    }

    private function count(QueryBuilder $queryBuilder): int
    {
        $count = clone $queryBuilder;
        $count->select('COUNT(1) AS total');

        $query = $count->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($this->cacheLifetime, sprintf('%s:%s', __CLASS__, __METHOD__));

        return (int) $query->getSingleScalarResult();
    }

    private function pagination(Request $request): Pagination
    {
        return new Pagination((int) $request->query->get($this->pageField, 1), (int) $request->query->get($this->perPageField, $this->perPageDefault));
    }
}
