<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Cron;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Repository\CronJobRepository;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronService extends AbstractService
{
    public function __construct(CronJobRepository $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($repository, $aliasHelper);
    }
}
