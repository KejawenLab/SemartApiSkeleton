<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Pagination\Query;

use Doctrine\ORM\QueryBuilder;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SortByCreateAtExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $queryBuilder->addOrderBy(sprintf('%s.createdAt', $this->aliasHelper->findAlias('root')), 'DESC');
    }

    public function support(string $class): bool
    {
        return in_array(TimestampableEntity::class, (new \ReflectionClass($class))->getTraitNames());
    }
}
