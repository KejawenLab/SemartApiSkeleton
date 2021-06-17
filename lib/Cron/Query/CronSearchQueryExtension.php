<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronSearchQueryExtension extends AbstractQueryExtension
{
    public function support(string $class, Request $request): bool
    {
        return in_array(CronInterface::class, class_implements($class)) && $request->query->get('q');
    }

    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');

        $alias = $this->aliasHelper->findAlias('root');
        $queryBuilder->orWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.name)', $alias),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($query))
                )
            )
        );
        $queryBuilder->orWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.command)', $alias),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($query))
                )
            )
        );
    }
}
