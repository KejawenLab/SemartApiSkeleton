<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Query\Group;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Pagination\Model\QueryExtensionInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PermissionJoinExtension implements QueryExtensionInterface
{
    protected $aliasHelper;

    public function __construct(AliasHelper $aliasHelper)
    {
        $this->aliasHelper = $aliasHelper;
    }

    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $groupId = $request->attributes->get('id');
        if (!$groupId) {
            return;
        }

        $root = $this->aliasHelper->findAlias('root');
        $alias = $this->aliasHelper->findAlias('group');
        $queryBuilder->innerJoin(sprintf('%s.group', $root), $alias);
        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('%s.id', $alias), $queryBuilder->expr()->literal($groupId)));
    }

    public function support(string $class): bool
    {
        return in_array(PermissionInterface::class, class_implements($class));
    }
}
