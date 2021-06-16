<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron;

use Cron\Report\CronReport;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Cron\Executor;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Redis;
use ReflectionException;
use ReflectionObject;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class ExecutorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testStartProcesses(): void
    {
        $redis = $this->createMock(Redis::class);

        $service = $this->createMock(CronService::class);
        $service->expects($this->any())->method('get')->withAnyParameters();
        $service->expects($this->any())->method('save')->withAnyParameters();

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->any())->method('info')->withAnyParameters();

        $report = new CronReport();

        $executor = new Executor($redis, $service, $logger);

        $reflection = new ReflectionObject($executor);
        $method = $reflection->getMethod('startProcesses');
        $method->setAccessible(true);

        $method->invokeArgs($executor, [$report]);

        $this->assertSame($report, $report);
    }
}
