<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Repository;

use Alpabit\ApiSkeleton\Cron\Model\CronReportRepositoryInterface;
use Alpabit\ApiSkeleton\Entity\CronReport;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method CronReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method CronReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method CronReport[]    findAll()
 * @method CronReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronReportRepository extends AbstractRepository implements CronReportRepositoryInterface
{
    public function __construct(EventDispatcherInterface $eventDispatcher, ManagerRegistry $registry)
    {
        parent::__construct($eventDispatcher, $registry, CronReport::class);
    }
}
