<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\Semart\ApiSkeleton\Entity\Group;
use KejawenLab\Semart\ApiSkeleton\Security\Model\GroupRepositoryInterface;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class GroupRepository extends AbstractRepository implements GroupRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }
}
