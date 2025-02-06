<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

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
    public function __construct(RequestStack $requestStack, ManagerRegistry $registry)
    {
        parent::__construct($requestStack, $registry, User::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function isSupervisor(UserInterface $user, UserInterface $supervisor): bool
    {
        if ($user->getSupervisor() && $user->getSupervisor()->getId() === $supervisor->getId()) {
            return true;
        }

        if (null === $user->getSupervisor()) {
            return $user->getGroup()->getCode() === GroupInterface::SUPER_ADMIN_CODE;
        }

        return $this->isSupervisor($user->getSupervisor(), $supervisor);
    }

    public function findByUsername(string $username): ?UserInterface
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if ($deviceId !== '' && $deviceId !== '0') {
            $cacheLifetime = SemartApiSkeleton::QUERY_CACHE_LIFETIME;
        }

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('LOWER(o.username)', $queryBuilder->expr()->literal(StringUtil::lowercase($username))));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        if (!$this->isDisableCache()) {
            $query->useQueryCache(true);
            $query->enableResultCache($cacheLifetime, sprintf('%s_%s_%s_%s', $deviceId, sha1(self::class), sha1(__METHOD__), $username));
        }

        return $query->getOneOrNullResult();
    }

    public function findByDeviceId(string $deviceId): ?UserInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.deviceId', $queryBuilder->expr()->literal($deviceId)));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        if (!$this->isDisableCache()) {
            $query->useQueryCache(true);
            $query->enableResultCache(self::MICRO_CACHE, sprintf('%s_%s_%s', sha1(self::class), sha1(__METHOD__), $deviceId));
        }

        return $query->getOneOrNullResult();
    }
}
