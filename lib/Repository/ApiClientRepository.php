<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;

/**
 * @method ApiClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiClient[]    findAll()
 * @method ApiClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class ApiClientRepository extends AbstractRepository implements ApiClientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiClient::class);
    }

    public function findByApiKey(string $apiKey): ?ApiClientInterface
    {
        return $this->findOneBy(['apiKey' => $apiKey]);
    }

    public function countByUser(UserInterface $user): int
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->select('COUNT(1) as total');
        $queryBuilder->innerJoin('o.user', 'u');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('u.id', $queryBuilder->expr()->literal($user->getId())));

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf('%s:%s', self::class, __METHOD__));

        return (int) $query->getSingleScalarResult();
    }

    public function findByIdAndUser(string $id, UserInterface $user): ?ApiClientInterface
    {
        return $this->findOneBy(['id' => $id, 'user' => $user]);
    }
}
