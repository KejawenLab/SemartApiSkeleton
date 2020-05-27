<?php

namespace App\Repository;

use App\Entity\Setting;
use App\Pagination\Pagination;
use App\Setting\Model\SettingInterface;
use App\Setting\Model\SettingRepositoryInterface;
use App\Util\StringUtil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends ServiceEntityRepository implements SettingRepositoryInterface
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

    public function paginate(Pagination $pagination, array $filters): array
    {
        $queryBuilder = $this->queryBuilder($filters);
        $queryBuilder->setMaxResults($pagination->getPerPage());
        $queryBuilder->setFirstResult(($pagination->getPage() - 1) * $pagination->getPerPage());

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        $cacheLifetime = $this->findByParameter('CACHE_LIFETIME');
        if ($cacheLifetime) {
            $query->enableResultCache($cacheLifetime->getValue(), sprintf('%s:%s:%s', __CLASS__, __METHOD__, serialize($queryBuilder->getParameters())));
        }

        return $query->getResult();
    }

    public function count(array $filters)
    {
        $queryBuilder = $this->queryBuilder($filters);
        $queryBuilder->select('COUNT(1) AS total');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        $cacheLifetime = $this->findByParameter('CACHE_LIFETIME');
        if ($cacheLifetime) {
            $query->enableResultCache($cacheLifetime->getValue(), sprintf('%s:%s:%s', __CLASS__, __METHOD__, serialize($queryBuilder->getParameters())));
        }

        return $query->getSingleScalarResult();
    }

    private function queryBuilder(array $filters): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $expr = $queryBuilder->expr();
        foreach ($filters as $field => $value) {
            $queryBuilder->andWhere($expr->eq($field, $expr->literal($value)));
        }

        return $queryBuilder;
    }
}
