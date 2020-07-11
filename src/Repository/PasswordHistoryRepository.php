<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Repository;

use Alpabit\ApiSkeleton\Entity\PasswordHistory;
use Alpabit\ApiSkeleton\Security\Model\PasswordHistoryRepositoryInterface;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordHistory[]    findAll()
 * @method PasswordHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>ssss
 */
final class PasswordHistoryRepository extends AbstractRepository implements PasswordHistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordHistory::class);
    }

    public function findPassword(UserInterface $user): array
    {
        return $this->findBy(['source' => get_class($user), 'identifier' => $user->getId()], ['createdAt' => 'DESC'], 17);
    }
}
