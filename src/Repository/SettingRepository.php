<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Repository;

use KejawenLab\Semart\ApiSkeleton\Entity\Setting;
use KejawenLab\Semart\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\Semart\ApiSkeleton\Setting\Model\SettingRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Util\StringUtil;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends AbstractRepository implements SettingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function findByParameter(string $parameter): ?SettingInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.parameter', $queryBuilder->expr()->literal(StringUtil::uppercase($parameter))));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(1, sprintf('%s:%s:%s', __CLASS__, __METHOD__, serialize($queryBuilder->getParameters())));

        return $query->getOneOrNullResult();
    }
}
