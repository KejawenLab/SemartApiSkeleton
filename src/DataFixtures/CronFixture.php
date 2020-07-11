<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\DataFixtures;

use Alpabit\ApiSkeleton\Entity\Cron;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronFixture extends AbstractFixture
{
    protected function createNew()
    {
        return new Cron();
    }

    protected function getReferenceKey(): string
    {
        return 'cron';
    }
}
