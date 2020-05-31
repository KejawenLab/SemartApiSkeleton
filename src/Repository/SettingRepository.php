<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Alpabit\ApiSkeleton\Entity\Setting;
use Alpabit\ApiSkeleton\Setting\Model\SettingInterface;
use Alpabit\ApiSkeleton\Setting\Model\SettingRepositoryInterface;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class SettingRepository extends AbstractRepository implements SettingRepositoryInterface
{
    public function __construct(EventDispatcherInterface $eventDispatcher, ManagerRegistry $registry)
    {
        parent::__construct($eventDispatcher, $registry, Setting::class);
    }

    public function findByParameter(string $parameter): ?SettingInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.parameter', $queryBuilder->expr()->literal(StringUtil::uppercase($parameter))));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(static::MICRO_CACHE, sprintf('%s:%s:%s', __CLASS__, __METHOD__, serialize($queryBuilder->getParameters())));

        return $query->getOneOrNullResult();
    }
}
