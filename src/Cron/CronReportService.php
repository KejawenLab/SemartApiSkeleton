<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Repository\CronReportRepository;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Setting\Model\SettingRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronReportService extends AbstractService
{
    public function __construct(MessageBusInterface $messageBus, SettingRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }
}
