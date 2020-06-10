<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Cron\Model\CronReportRepositoryInterface;
use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronReportService extends AbstractService
{
    public function __construct(MessageBusInterface $messageBus, CronReportRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }
}
