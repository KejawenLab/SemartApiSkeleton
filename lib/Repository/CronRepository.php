<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Cron\Model\CronRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method Cron|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cron|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cron[]    findAll()
 * @method Cron[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronRepository extends AbstractRepository implements CronRepositoryInterface
{
    public function __construct(RequestStack $requestStack, ManagerRegistry $registry)
    {
        parent::__construct($requestStack, $registry, Cron::class);
    }

    public function findUnRunningCrons(): array
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if (!empty($deviceId)) {
            $cacheLifetime = SemartApiSkeleton::STATIC_CACHE_LIFETIME;
        }

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.running', $queryBuilder->expr()->literal(false)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.enabled', $queryBuilder->expr()->literal(true)));
        $queryBuilder->addOrderBy('o.name', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache($cacheLifetime, sprintf("%s_%s_%s_%s", $deviceId, sha1(self::class), sha1(__METHOD__), sha1($query->getSQL())));

        return $query->getResult();
    }
}
