<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class MenuRepository extends AbstractRepository implements MenuRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findByCode(string $code): ?MenuInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('UPPER(o.code)', $queryBuilder->expr()->literal(StringUtil::uppercase($code))));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf('%s:%s:%s', self::class, __METHOD__, $code));

        return $query->getOneOrNullResult();
    }

    public function findChilds(MenuInterface $menu): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->innerJoin('o.parent', 'p');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('p.id', $queryBuilder->expr()->literal($menu->getId())));
        $queryBuilder->addOrderBy('o.sortOrder', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf('%s:%s:%s', self::class, __METHOD__, $menu->getId()));

        $menus = $query->getResult();
        foreach ($menus as $menu) {
            yield $menu;
        }
    }
}
