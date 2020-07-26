<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronReportSearchQueryExtension extends AbstractCronReportExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $queryBuilder->andWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.output)', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($request->query->get('q'))))
            )
        );
    }
}
