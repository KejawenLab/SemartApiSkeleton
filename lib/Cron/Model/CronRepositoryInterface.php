<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface CronRepositoryInterface extends PaginatableRepositoryInterface
{
    /**
     * @return mixed[]
     */
    public function findUnRunningCrons(): array;
}
