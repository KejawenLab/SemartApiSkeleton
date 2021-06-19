<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronReportSearchQueryExtension extends AbstractCronReportExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $queryBuilder->andWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.output)', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))
            )
        );
    }
}
