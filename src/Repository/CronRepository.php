<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use KejawenLab\ApiSkeleton\Cron\Model\CronRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\Cron;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cron|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cron|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cron[]    findAll()
 * @method Cron[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronRepository extends AbstractRepository implements CronRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cron::class);
    }
}
