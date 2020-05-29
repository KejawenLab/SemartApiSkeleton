<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Cron\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronReportSearchQueryExtension extends AbstractCronReportExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $alias = $this->aliasHelper->findAlias('root');
        $queryBuilder->andWhere($queryBuilder->expr()->like(sprintf('UPPER(%s.output)', $alias), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
    }
}
