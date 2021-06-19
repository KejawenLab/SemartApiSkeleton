<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class FilterParameterExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $filter = $request->query->get('parameter');
        if (!$filter) {
            return;
        }

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                sprintf('UPPER(%s.parameter)', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(StringUtil::uppercase($filter))
            )
        );
    }
}
