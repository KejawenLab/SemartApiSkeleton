<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Query;

use KejawenLab\ApiSkeleton\Cron\Model\CronReportInterface;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractCronReportExtension extends Base
{
    public function support(string $class, Request $request): bool
    {
        return \in_array(CronReportInterface::class, class_implements($class));
    }
}
