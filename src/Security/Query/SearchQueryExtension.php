<?php

declare(strict_types=1);

namespace App\Security\Query;

use App\Util\StringUtil;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SearchQueryExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $alias = $this->aliasHelper->findAlias('root');
        $queryBuilder->orWhere($queryBuilder->expr()->like(sprintf('%s.code', $alias), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
        $queryBuilder->orWhere($queryBuilder->expr()->like(sprintf('%s.name', $alias), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
    }
}
