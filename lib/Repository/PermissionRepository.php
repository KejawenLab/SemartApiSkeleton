<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Iterator;
use KejawenLab\ApiSkeleton\Entity\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRepositoryInterface;
use Swoole\Coroutine;

/**
 * @method Permission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permission[]    findAll()
 * @method Permission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PermissionRepository extends AbstractRepository implements PermissionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permission::class);
    }

    public function findPermission(GroupInterface $group, MenuInterface $menu): ?PermissionInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->innerJoin('o.group', 'g');
        $queryBuilder->innerJoin('o.menu', 'm');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('g.id', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('m.id', $queryBuilder->expr()->literal($menu->getId())));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }

    /**
     * @return Iterator
     */
    public function findPermissions(GroupInterface $group, iterable $menus): iterable
    {
        $ids = [];
        /** @var MenuInterface $menu */
        foreach ($menus as $menu) {
            $ids[] = $menu->getId();
        }

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->innerJoin('o.group', 'g');
        $queryBuilder->innerJoin('o.menu', 'm');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('g.id', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->add('where', $queryBuilder->expr()->in('m.id', ':ids'));
        $queryBuilder->setParameter('ids', $ids);
        $queryBuilder->addOrderBy('m.sortOrder', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        $permissions = $query->getResult();
        foreach ($permissions as $permission) {
            yield $permission;
        }
    }

    /**
     * @return Iterator<MenuInterface|null>
     */
    public function findAllowedMenusByGroup(GroupInterface $group, bool $parentOnly = false): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->innerJoin('o.group', 'g');
        $queryBuilder->innerJoin('o.menu', 'm');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('g.id', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('m.showable', $queryBuilder->expr()->literal(true)));
        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->eq('o.addable', $queryBuilder->expr()->literal(true)),
            $queryBuilder->expr()->eq('o.editable', $queryBuilder->expr()->literal(true)),
            $queryBuilder->expr()->eq('o.viewable', $queryBuilder->expr()->literal(true)),
        ));
        $queryBuilder->addOrderBy('m.sortOrder', 'ASC');
        if ($parentOnly) {
            $queryBuilder->andWhere($queryBuilder->expr()->isNull('m.parent'));
        }

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        /** @var PermissionInterface[] $permissions */
        $permissions = $query->getResult();
        foreach ($permissions as $permission) {
            yield $permission->getMenu();
        }
    }

    /**
     * @return Iterator<MenuInterface|null>
     */
    public function findAllowedChildMenusByGroupAndMenu(GroupInterface $group, MenuInterface $menu): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->innerJoin('o.group', 'g');
        $queryBuilder->innerJoin('o.menu', 'm');
        $queryBuilder->innerJoin('m.parent', 'p');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('g.id', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('p.id', $queryBuilder->expr()->literal($menu->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('m.showable', $queryBuilder->expr()->literal(true)));
        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->eq('o.addable', $queryBuilder->expr()->literal(true)),
            $queryBuilder->expr()->eq('o.editable', $queryBuilder->expr()->literal(true)),
            $queryBuilder->expr()->eq('o.viewable', $queryBuilder->expr()->literal(true)),
        ));
        $queryBuilder->addOrderBy('m.sortOrder', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        /** @var PermissionInterface[] $permissions */
        $permissions = $query->getResult();
        foreach ($permissions as $permission) {
            yield $permission->getMenu();
        }
    }

    public function removeByGroup(GroupInterface $group): void
    {
        $queryBuilder = $this->createQueryBuilder('o');
        Coroutine::create(function () use ($queryBuilder, $group): void {
            $queryBuilder->update();
            $queryBuilder->set('o.deletedAt', ':now');
            $queryBuilder->where('o.group = :group');
            $queryBuilder->setParameter('now', new DateTime());
            $queryBuilder->setParameter('group', $group);
            $queryBuilder->getQuery()->execute();
        });
    }

    public function removeByMenu(MenuInterface $menu): void
    {
        $queryBuilder = $this->createQueryBuilder('o');
        Coroutine::create(function () use ($queryBuilder, $menu): void {
            $queryBuilder->update();
            $queryBuilder->set('o.deletedAt', ':now');
            $queryBuilder->where('o.menu= :menu');
            $queryBuilder->setParameter('menu', $menu);
            $queryBuilder->setParameter('now', new DateTime());
            $queryBuilder->getQuery()->execute();
        });
    }
}
