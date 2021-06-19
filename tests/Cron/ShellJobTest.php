<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron;

use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\ShellJob;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ShellJobTest extends TestCase
{
    public function testGetCron(): void
    {
        $cron = $this->createMock(CronInterface::class);

        $shellJob = new ShellJob($cron);

        $this->assertSame($cron, $shellJob->getCron());
    }
}
