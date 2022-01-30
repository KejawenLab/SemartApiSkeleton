<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Media\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SearchQueryExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                sprintf('%s.hidden', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(false)
            )
        );
        $queryBuilder->andWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.fileName)', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($request->query->get('q')))
                )
            )
        );
    }

    public function support(string $class, Request $request): bool
    {
        return in_array(MediaInterface::class, class_implements($class)) && $request->query->get('q');
    }
}
