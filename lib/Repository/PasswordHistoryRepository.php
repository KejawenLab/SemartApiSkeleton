<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\PasswordHistory;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;

/**
 * @method PasswordHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordHistory[]    findAll()
 * @method PasswordHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>ssss
 */
final class PasswordHistoryRepository extends AbstractRepository implements PasswordHistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordHistory::class);
    }

    /**
     * @return PasswordHistory[]
     */
    public function findPasswords(UserInterface $user): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.source', $queryBuilder->expr()->literal($user::class)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.id', $queryBuilder->expr()->literal($user->getId())));
        $queryBuilder->addOrderBy('o.createdAt', 'DESC');
        $queryBuilder->setMaxResults(17);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf('%s:%s:%s', self::class, __METHOD__, $user->getId()));

        $passwordHistories = $query->getResult();
        foreach ($passwordHistories as $passwordHistory) {
            yield $passwordHistory;
        }
    }
}
