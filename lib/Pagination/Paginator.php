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
    private readonly ?string $pageField;

    private readonly ?string $perPageField;

    private readonly int $perPageDefault;

    private readonly int $cacheLifetime;

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
     *
     *
     *
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @return array<string, mixed[]>
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
        $query->enableResultCache($this->cacheLifetime, sprintf(
            '%s_%s_%s_%s',
            str_replace([':', '/', '\\'], "_", self::class),
            str_replace([':', '/', '\\'], "_", __METHOD__),
            $page,
            $perPage,
        ));

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
        $count->resetDQLPart('orderBy');

        $query = $count->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($this->cacheLifetime, sprintf(
            '%s_%s',
            str_replace([':', '/', '\\'], "_", self::class),
            str_replace([':', '/', '\\'], "_", __METHOD__),
        ));

        return (int) $query->getSingleScalarResult();
    }
}
