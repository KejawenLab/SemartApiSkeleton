<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Pagination\Model\QueryExtensionInterface;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Paginator
{
    private readonly ?string $pageField;

    private readonly ?string $perPageField;

    private readonly int $perPageDefault;

    private int $cacheLifetime;

    /**
     * @param QueryExtensionInterface[] $queryExtension
     */
    public function __construct(SettingService $setting, private readonly iterable $queryExtension)
    {
        $this->pageField = $setting->getPageField();
        $this->perPageField = $setting->getPerPageField();
        $this->perPageDefault = $setting->getRecordPerPage();
        $this->cacheLifetime = $setting->getCacheLifetime();
    }

    /**
     * @return array<string, array>
     * @throws NonUniqueResultException
     *
     * @throws NoResultException
     */
    public function paginate(QueryBuilder $queryBuilder, Request $request, string $class): array
    {
        $page = (int)$request->query->get($this->pageField, 1);
        $perPage = (int)$request->query->get($this->perPageField, $this->perPageDefault);
        foreach ($this->queryExtension as $extension) {
            if ($extension->support($class, $request)) {
                $extension->apply($queryBuilder, $request);
            }
        }

        $deviceId = $request->getSession()->get(SemartApiSkeleton::USER_DEVICE_ID, '');
        if (SemartApiSkeleton::API_CLIENT_DEVICE_ID === $deviceId) {
            $deviceId = '';
        }

        $disableCache = false;
        if (!empty($request->query->get(SemartApiSkeleton::DISABLE_QUERY_CACHE_QUERY_STRING))) {
            $disableCache = true;
        }

        if (!empty($deviceId)) {
            $this->cacheLifetime = SemartApiSkeleton::QUERY_CACHE_LIFETIME;
        }

        $total = $this->count($queryBuilder, $deviceId, $disableCache);

        return [
            'page' => $page,
            'per_page' => $perPage,
            'total_page' => ceil($total / $perPage),
            'total_item' => $total,
            'items' => $this->paging($queryBuilder, $deviceId, $page, $perPage, $disableCache),
        ];
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function count(QueryBuilder $queryBuilder, string $deviceId, bool $disableCache = false): int
    {
        $count = clone $queryBuilder;
        $groupByParts = $count->getDQLPart('groupBy');

        if (empty($groupByParts)) {
            $count->select('COUNT(1) as total');
        } else {
            $count->select(sprintf('COUNT(DISTINCT %s) as total', implode(', ', $groupByParts)));
            $count->resetDQLPart('groupBy');
        }

        $count->resetDQLPart('orderBy');

        $query = $count->getQuery();
        if (!$disableCache) {
            $query->useQueryCache(true);
            $query->enableResultCache($this->cacheLifetime, sprintf('%s_%s_%s_%s', sha1(self::class), sha1(__METHOD__), $deviceId, sha1(serialize($query->getParameters()))));
        }

        return (int)$query->getSingleScalarResult();
    }

    private function paging(QueryBuilder $queryBuilder, string $deviceId, int $page, int $perPage, bool $disableCache = false): array
    {
        $queryBuilder->setMaxResults($perPage);
        $queryBuilder->setFirstResult(($page - 1) * $perPage);

        $query = $queryBuilder->getQuery();
        if (!$disableCache) {
            $query->useQueryCache(true);
            $query->enableResultCache($this->cacheLifetime, sprintf('%s_%s_%s_%d_%d_%s', sha1(self::class), sha1(__METHOD__), $deviceId, $page, $perPage, sha1(serialize($query->getParameters()))));
        }

        return $query->getResult();
    }
}
