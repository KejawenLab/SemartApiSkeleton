<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Alpabit\ApiSkeleton\Entity\Permission;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method Permission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permission[]    findAll()
 * @method Permission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PermissionRepository extends AbstractRepository implements PermissionRepositoryInterface
{
    public function __construct(EventDispatcherInterface $eventDispatcher, ManagerRegistry $registry)
    {
        parent::__construct($eventDispatcher, $registry, Permission::class);
    }

    public function findPermission(GroupInterface $group, MenuInterface $menu): ?PermissionInterface
    {
        return $this->findOneBy(['group' => $group, 'menu' => $menu]);
    }

    public function findAllowedMenusByGroup(GroupInterface $group, bool $parentOnly = false): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->innerJoin('o.group', 'g');
        $queryBuilder->innerJoin('o.menu', 'm');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('g.id', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.viewable', $queryBuilder->expr()->literal(true)));
        $queryBuilder->addOrderBy('m.sortOrder', 'ASC');
        if ($parentOnly) {
            $queryBuilder->andWhere($queryBuilder->expr()->isNull('m.parent'));
        }

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(static::MICRO_CACHE, sprintf('%s:%s:%s', __CLASS__, __METHOD__, serialize($query->getParameters())));

        /** @var PermissionInterface[] $permissions */
        $menus = [];
        $permissions = $query->getResult();
        foreach ($permissions as $permission) {
            $menus[] = $permission->getMenu();
        }

        return $menus;
    }

    public function removeByGroup(GroupInterface $group): void
    {
        $queryBuilder = $this->createQueryBuilder('o')->update();
        $queryBuilder->set('o.deletedAt', ':now');
        $queryBuilder->where('o.group = :group');
        $queryBuilder->setParameter('now', new \DateTime());
        $queryBuilder->setParameter('group', $group);
        $queryBuilder->getQuery()->execute();
    }

    public function removeByMenu(MenuInterface $menu): void
    {
        $queryBuilder = $this->createQueryBuilder('o')->update();
        $queryBuilder->set('o.deletedAt', ':now');
        $queryBuilder->where('o.menu= :menu');
        $queryBuilder->setParameter('menu', $menu);
        $queryBuilder->setParameter('now', new \DateTime());
        $queryBuilder->getQuery()->execute();
    }
}
