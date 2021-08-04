<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Pagination\Model\QueryExtensionInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Paginator
{
    private ?string $pageField;

    private ?string $perPageField;

    private int $perPageDefault;

    private int $cacheLifetime;

    public function __construct(SettingService $setting, /**
     * @var QueryExtensionInterface[]
     */
    private iterable $queryExtension)
    {
        $this->pageField = $setting->getPageField();
        $this->perPageField = $setting->getPerPageField();
        $this->perPageDefault = $setting->getRecordPerPage();
        $this->cacheLifetime = $setting->getCacheLifetime();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function paginate(QueryBuilder $queryBuilder, Request $request, string $class): array
    {
        $page = (int) $request->query->get($this->pageField, 1);
        $perPage = (int) $request->query->get($this->perPageField, $this->perPageDefault);
        foreach ($this->queryExtension as $extension) {
            if ($extension->support($class, $request)) {
                $extension->apply($queryBuilder, $request);
            }
        }

        $total = $this->count($queryBuilder);

        return [
            'page' => $page,
            'per_page' => $perPage,
            'total_page' => ceil($total / $perPage),
            'total_item' => $total,
            'items' => $this->paging($queryBuilder, $page, $perPage),
        ];
    }

    private function paging(QueryBuilder $queryBuilder, int $page, int $perPage): array
    {
        $queryBuilder->setMaxResults($perPage);
        $queryBuilder->setFirstResult(($page - 1) * $perPage);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($this->cacheLifetime, sprintf('%s:%s:%s:%s', self::class, __METHOD__, $page, $perPage));

        return $query->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function count(QueryBuilder $queryBuilder): int
    {
        $count = clone $queryBuilder;
        $count->select('COUNT(1) AS total');

        $query = $count->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($this->cacheLifetime, sprintf('%s:%s', self::class, __METHOD__));

        return (int) $query->getSingleScalarResult();
    }
}
