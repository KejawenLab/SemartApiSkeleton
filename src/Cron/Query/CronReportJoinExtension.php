<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Query;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronReportJoinExtension extends AbstractCronReportExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $cronId = $request->attributes->get('id');
        if (!$cronId) {
            return;
        }

        $alias = $this->aliasHelper->findAlias('cron');
        $queryBuilder->innerJoin(sprintf('%s.cron', $this->aliasHelper->findAlias('root')), $alias);
        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('%s.id', $alias), $queryBuilder->expr()->literal($cronId)));
    }
}
