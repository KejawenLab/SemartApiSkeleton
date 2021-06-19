<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\DataFixtures;

use KejawenLab\ApiSkeleton\Entity\Cron;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronFixture extends AbstractFixture
{
    protected function createNew(): Cron
    {
        return new Cron();
    }

    protected function getReferenceKey(): string
    {
        return 'cron';
    }
}
