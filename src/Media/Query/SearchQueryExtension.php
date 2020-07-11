<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Media\Query;

use Alpabit\ApiSkeleton\Media\Model\MediaInterface;
use Alpabit\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SearchQueryExtension extends AbstractQueryExtension
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $queryBuilder->andWhere($queryBuilder->expr()->like(sprintf('UPPER(%s.filePath)', $this->aliasHelper->findAlias('root')), $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))));
    }

    public function support(string $class): bool
    {
        return in_array(MediaInterface::class, class_implements($class));
    }
}
