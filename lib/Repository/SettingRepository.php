<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\Model\SettingRepositoryInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>ss
 */
final class SettingRepository extends AbstractRepository implements SettingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByParameter(string $parameter): ?SettingInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.parameter', $queryBuilder->expr()->literal(StringUtil::uppercase($parameter))));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf('%s:%s:%s', __CLASS__, __METHOD__, $parameter));

        return $query->getOneOrNullResult();
    }
}
