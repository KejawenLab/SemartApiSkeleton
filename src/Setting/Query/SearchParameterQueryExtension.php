<?php

declare(strict_types=1);

namespace App\Setting\Query;

use App\Util\StringUtil;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SearchParameterQueryExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $alias = $this->aliasHelper->findAlias('root');
        $queryBuilder->andWhere($queryBuilder->expr()->like(sprintf('%s.parameter', $alias), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
    }
}
