<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GroupRepository extends AbstractRepository implements GroupRepositoryInterface
{
    public function __construct(RequestStack $requestStack, ManagerRegistry $registry)
    {
        parent::__construct($requestStack, $registry, Group::class);
    }

    /**
     * @return mixed|null
     */
    public function findByCode(string $code)
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if ($deviceId !== '') {
            $cacheLifetime = SemartApiSkeleton::QUERY_CACHE_LIFETIME;
        }

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('UPPER(o.code)', $queryBuilder->expr()->literal(StringUtil::uppercase($code))));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        if (!$this->isDisableCache()) {
            $query->useQueryCache(true);
            $query->enableResultCache($cacheLifetime, sprintf('%s_%s_%s_%s', $deviceId, sha1(self::class), sha1(__METHOD__), $code));
        }

        return $query->getOneOrNullResult();
    }
}
