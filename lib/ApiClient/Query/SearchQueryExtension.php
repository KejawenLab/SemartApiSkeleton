<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SearchQueryExtension extends AbstractQueryExtension
{
    public function __construct(AliasHelper $aliasHelper)
    {
        parent::__construct($aliasHelper);
    }

    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $queryBuilder->andWhere(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.name)', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal(sprintf('%%%s%%', StringUtil::uppercase($query)))
            )
        );
    }

    public function support(string $class, Request $request): bool
    {
        return in_array(ApiClientInterface::class, class_implements($class));
    }
}
