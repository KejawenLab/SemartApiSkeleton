<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Cron\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\ApiSkeleton\Util\StringUtil;
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

        $alias = $this->aliasHelper->findAlias('root');
        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('UPPER(%s.name)', $alias), $queryBuilder->expr()->literal(StringUtil::uppercase($filter))));
    }
}
