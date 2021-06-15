<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination\Query;

use ReflectionClass;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SortByCreateAtExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $queryBuilder->addOrderBy(sprintf('%s.createdAt', $this->aliasHelper->findAlias('root')), 'DESC');
    }

    /**
     * @throws ReflectionException
     */
    public function support(string $class, Request $request): bool
    {
        return in_array(TimestampableEntity::class, (new ReflectionClass($class))->getTraitNames());
    }
}
