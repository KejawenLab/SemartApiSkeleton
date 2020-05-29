<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Cron\Query;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class CronReportJoinExtension extends AbstractCronReportExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $cronId = $request->attributes->get('id');
        if (!$cronId) {
            return;
        }

        $root = $this->aliasHelper->findAlias('root');
        $alias = $this->aliasHelper->findAlias('job');
        $queryBuilder->innerJoin(sprintf('%s.job', $root), $alias);
        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('%s.id', $alias), $queryBuilder->expr()->literal($cronId)));
    }
}
