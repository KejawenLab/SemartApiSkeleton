<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Repository;

use Alpabit\ApiSkeleton\Entity\Menu;
use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class MenuRepository extends AbstractRepository implements MenuRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findByCode(string $code): ?MenuInterface
    {
        return $this->findOneBy(['code' => StringUtil::uppercase($code)]);
    }

    public function findChilds(MenuInterface $menu): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->innerJoin('o.parent', 'p');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('p.id', $queryBuilder->expr()->literal($menu->getId())));
        $queryBuilder->addOrderBy('o.sortOrder', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(7, sprintf('%s:%s:%s', __CLASS__, __METHOD__, $menu->getId()));

        return $query->getResult();
    }
}
