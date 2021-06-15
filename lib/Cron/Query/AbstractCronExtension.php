<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Query;

use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
abstract class AbstractCronExtension extends Base
{
    public function support(string $class, Request $request): bool
    {
        return in_array(CronInterface::class, class_implements($class)) && $request->query->get('q');
    }
}
