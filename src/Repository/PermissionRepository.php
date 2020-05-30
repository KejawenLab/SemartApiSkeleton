<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\Semart\ApiSkeleton\Entity\Permission;
use KejawenLab\Semart\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\UserInterface;
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

    public function findByUser(UserInterface $user): array
    {
        return $this->findBy(['group' => $user->getGroup()]);
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
