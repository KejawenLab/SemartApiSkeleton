<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Iterator;
use KejawenLab\ApiSkeleton\Entity\PasswordHistory;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method PasswordHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordHistory[]    findAll()
 * @method PasswordHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PasswordHistoryRepository extends AbstractRepository implements PasswordHistoryRepositoryInterface
{
    public function __construct(RequestStack $requestStack, ManagerRegistry $registry)
    {
        parent::__construct($requestStack, $registry, PasswordHistory::class);
    }

    /**
     * @return Iterator
     */
    public function findPasswords(UserInterface $user): iterable
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if ($deviceId !== '') {
            $cacheLifetime = SemartApiSkeleton::QUERY_CACHE_LIFETIME;
        }

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.source', $queryBuilder->expr()->literal($user::class)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.identifier', $queryBuilder->expr()->literal($user->getId())));
        $queryBuilder->addOrderBy('o.createdAt', 'DESC');
        $queryBuilder->setMaxResults(7);

        $query = $queryBuilder->getQuery();
        if (!$this->isDisableCache()) {
            $query->useQueryCache(true);
            $query->enableResultCache($cacheLifetime, sprintf('%s_%s_%s_%s', $deviceId, sha1(self::class), sha1(__METHOD__), $user->getId()));
        }

        $passwordHistories = $query->getResult();
        foreach ($passwordHistories as $passwordHistory) {
            yield $passwordHistory;
        }
    }
}
