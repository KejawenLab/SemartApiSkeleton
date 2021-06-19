<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Query\User;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class FilterUsernameExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $filter = $request->query->get('username');
        if (!$filter) {
            return;
        }

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                sprintf('UPPER(%s.username)', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(StringUtil::uppercase($filter))
            )
        );
    }
}
