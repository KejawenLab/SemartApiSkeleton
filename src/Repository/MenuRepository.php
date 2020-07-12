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
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
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
        $query->enableResultCache(static::MICRO_CACHE, sprintf('%s:%s:%s', __CLASS__, __METHOD__, $menu->getId()));

        return $query->getResult();
    }
}
