<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Menu;
use App\Security\Model\MenuInterface;
use App\Security\Model\MenuRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends AbstractRepository implements MenuRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findByCode(string $code): ?MenuInterface
    {
        return $this->findOneBy(['code' => $code]);
    }
}
