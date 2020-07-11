<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Query\Menu;

use Alpabit\ApiSkeleton\Util\StringUtil;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class FilterCodeExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $filter = $request->query->get('code');
        if (!$filter) {
            return;
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('UPPER(%s.code)', $this->aliasHelper->findAlias('root')), $queryBuilder->expr()->literal(StringUtil::uppercase($filter))));
    }
}
