<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface as AppUser;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UserRepository extends AbstractRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, private string $superAdmin)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function isSupervisor(AppUser $user, AppUser $supervisor): bool
    {
        if ($user->getSupervisor() && $user->getSupervisor()->getId() === $supervisor->getId()) {
            return true;
        }

        if (null === $user->getSupervisor()) {
            return $user->getGroup()->getCode() === $this->superAdmin;
        }

        return $this->isSupervisor($user->getSupervisor(), $supervisor);
    }

    public function findByUsername(string $username): ?AppUser
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('LOWER(o.username)', $queryBuilder->expr()->literal(StringUtil::lowercase($username))));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf(
            '%s_%s_%s',
            str_replace([':', '/', '\\'], "_", self::class),
            str_replace([':', '/', '\\'], "_", __METHOD__),
            $username,
        ));

        return $query->getOneOrNullResult();
    }

    public function findByDeviceId(string $deviceId): ?AppUser
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.deviceId', $queryBuilder->expr()->literal($deviceId)));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf(
            '%s_%s_%s',
            str_replace([':', '/', '\\'], "_", self::class),
            str_replace([':', '/', '\\'], "_", __METHOD__),
            $deviceId,
        ));

        return $query->getOneOrNullResult();
    }
}
