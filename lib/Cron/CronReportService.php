<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use DateInterval;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use KejawenLab\ApiSkeleton\Cron\Model\CronReportRepositoryInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronReportService extends AbstractService
{
    public function __construct(MessageBusInterface $messageBus, CronReportRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countStale(): int
    {
        $stale = (new DateTime())->sub(new DateInterval('P3M'));

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('COUNT(1)');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->lt(
                sprintf('%s.createdAt', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal($stale->format('Y-m-d H:i:s'))
            )
        );

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function clean(): void
    {
        $stale = (new DateTime())->sub(new DateInterval('P3M'));

        $queryBuilder = $this->getQueryBuilder()->delete();
        $queryBuilder->andWhere(
            $queryBuilder->expr()->lt(
                sprintf('%s.createdAt', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal($stale->format('Y-m-d H:i:s'))
            )
        );
        $queryBuilder->getQuery()->execute();
    }
}
