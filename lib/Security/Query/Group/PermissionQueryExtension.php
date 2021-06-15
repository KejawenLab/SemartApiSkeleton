<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Query\Group;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Pagination\Model\QueryExtensionInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PermissionQueryExtension implements QueryExtensionInterface
{
    public function __construct(protected AliasHelper $aliasHelper)
    {
    }

    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $groupAlias = $this->aliasHelper->findAlias('group');
        $queryBuilder->innerJoin(sprintf('%s.group', $this->aliasHelper->findAlias('root')), $groupAlias);
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                sprintf('%s.id', $groupAlias),
                $queryBuilder->expr()->literal($request->attributes->get('id'))
            )
        );

        $query = $request->query->get('q');
        if (!$query) {
            return;
        }

        $menuAlias = $this->aliasHelper->findAlias('menu');
        $queryBuilder->innerJoin(sprintf('%s.menu', $this->aliasHelper->findAlias('root')), $menuAlias);
        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.code)', $groupAlias),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($query))
                )
            ),
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.name)', $groupAlias),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($query))
                )
            ),
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.code)', $menuAlias),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($query))
                )
            ),
            $queryBuilder->expr()->like(
                sprintf('UPPER(%s.name)', $menuAlias),
                $queryBuilder->expr()->literal(
                    sprintf('%%%s%%', StringUtil::uppercase($query))
                )
            ),
        ));
    }

    public function support(string $class, Request $request): bool
    {
        return in_array(PermissionInterface::class, class_implements($class)) && $request->attributes->get('id');
    }
}
