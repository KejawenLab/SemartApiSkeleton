<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class FilterParameterOrGroupExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $parameter = $request->query->get('parameter');
        if (null !== $parameter) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    sprintf('UPPER(%s.parameter)', $this->aliasHelper->findAlias('root')),
                    $queryBuilder->expr()->literal(StringUtil::uppercase($parameter))
                )
            );
        }

        $group = $request->query->get('group');
        if (null !== $group) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    sprintf('%s.group', $this->aliasHelper->findAlias('root')),
                    $queryBuilder->expr()->literal(StringUtil::lowercase($parameter))
                )
            );
        }
    }
}
