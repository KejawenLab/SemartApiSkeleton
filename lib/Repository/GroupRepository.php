<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * @return mixed|null
     */
    public function findByCode(string $code)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('UPPER(o.code)', $queryBuilder->expr()->literal(StringUtil::uppercase($code))));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf(
            '%s_%s_%s',
            str_replace([':', '/', '\\'], "_", self::class),
            str_replace([':', '/', '\\'], "_", __METHOD__),
            $code,
        ));

        return $query->getOneOrNullResult();
    }
}
