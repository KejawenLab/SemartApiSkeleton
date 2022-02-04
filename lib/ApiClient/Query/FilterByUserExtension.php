<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class FilterByUserExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $filter = $request->attributes->get('userId');
        if (!$filter) {
            return;
        }

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                sprintf('%s.user', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal($filter)
            )
        );
    }

    public function support(string $class, Request $request): bool
    {
        return \in_array(ApiClientInterface::class, class_implements($class));
    }
}
