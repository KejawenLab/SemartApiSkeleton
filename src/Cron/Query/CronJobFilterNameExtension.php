<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Query;

use Alpabit\ApiSkeleton\Util\StringUtil;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronJobFilterNameExtension extends AbstractCronJobExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $filter = $request->query->get('name');
        if (!$filter) {
            return;
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('UPPER(%s.name)', $this->aliasHelper->findAlias('root')), $queryBuilder->expr()->literal(StringUtil::uppercase($filter))));
    }
}
