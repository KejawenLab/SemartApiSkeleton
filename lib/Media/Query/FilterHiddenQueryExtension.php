<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Media\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class FilterHiddenQueryExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('%s.hidden', $this->aliasHelper->findAlias('root')), $queryBuilder->expr()->literal(false)));
    }

    public function support(string $class, Request $request): bool
    {
        return in_array(MediaInterface::class, class_implements($class));
    }
}
