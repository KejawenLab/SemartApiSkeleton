<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination\Query;

use Doctrine\ORM\QueryBuilder;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SortByUpdatedAtExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $queryBuilder->addOrderBy(sprintf('%s.updatedAt', $this->aliasHelper->findAlias('root')), 'DESC');
    }

    /**
     * @throws ReflectionException
     */
    public function support(string $class, Request $request): bool
    {
        return in_array(TimestampableEntity::class, (new ReflectionClass($class))->getTraitNames());
    }
}
