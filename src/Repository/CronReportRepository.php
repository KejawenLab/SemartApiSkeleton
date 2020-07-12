<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use KejawenLab\ApiSkeleton\Cron\Model\CronReportRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\CronReport;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CronReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method CronReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method CronReport[]    findAll()
 * @method CronReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronReportRepository extends AbstractRepository implements CronReportRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CronReport::class);
    }
}
