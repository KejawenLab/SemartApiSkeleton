<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use KejawenLab\ApiSkeleton\Cron\CronReportService;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronReportServiceTest extends TestCase
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testCountStale(): void
    {
        $service = $this->createMock(CronReportService::class);
        $service->expects($this->once())->method('countStale')->willReturn(1);

        $this->assertSame(1, $service->countStale());
    }

    public function testClean(): void
    {
        $service = $this->createMock(CronReportService::class);
        $service->expects($this->once())->method('clean');

        $service->clean();
    }
}
