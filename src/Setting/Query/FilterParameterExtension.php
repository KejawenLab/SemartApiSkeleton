<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Setting\Query;

use Doctrine\ORM\QueryBuilder;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class FilterParameterExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $filter = $request->query->get('parameter');
        if (!$filter) {
            return;
        }

        $alias = $this->aliasHelper->findAlias('root');
        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('UPPER(%s.parameter)', $alias), $queryBuilder->expr()->literal(StringUtil::uppercase($filter))));
    }
}
