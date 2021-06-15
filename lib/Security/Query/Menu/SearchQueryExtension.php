<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Query\Menu;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
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
        $queryBuilder->orWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.code)', $alias),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($query))
                )
            )
        );
        $queryBuilder->orWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.name)', $alias),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($query))
                )
            )
        );
    }
}
