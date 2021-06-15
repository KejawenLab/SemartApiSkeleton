<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Query;

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

        $queryBuilder->andWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.parameter)', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))
            )
        );
    }
}
