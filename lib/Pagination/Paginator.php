<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Pagination\Model\QueryExtensionInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
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
        $page = (int) $request->query->get($this->pageField, 1);
        $perPage = (int) $request->query->get($this->perPageField, $this->perPageDefault);
        foreach ($this->queryExtension as $extension) {
            if ($extension->support($class, $request)) {
                $extension->apply($queryBuilder, $request);
            }
        }

        return [
            'page' => $page,
            'per_page' => $perPage,
            'total_page' => ceil($this->count($queryBuilder) / $perPage),
            'total_item' => $this->count($queryBuilder),
            'items' => $this->paging($queryBuilder, $page, $perPage),
        ];
    }

    private function paging(QueryBuilder $queryBuilder, int $page, int $perPage): array
    {
        $queryBuilder->setMaxResults($perPage);
        $queryBuilder->setFirstResult(($page - 1) * $perPage);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($this->cacheLifetime, sprintf('%s:%s:%s:%s', __CLASS__, __METHOD__, $page, $perPage));

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
}
