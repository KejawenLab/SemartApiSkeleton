<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron;

use KejawenLab\ApiSkeleton\Cron\CronBuilder;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Repository\CronRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronServiceTest extends TestCase
{
    public function testResolve(): void
    {
        $cron = new Cron();

        $bus = $this->createMock(MessageBusInterface::class);

        $repository = $this->createMock(CronRepository::class);
        $repository->expects($this->once())->method('findBy')->withAnyParameters()->willReturn([$cron]);

        $aliasHelper = new AliasHelper();

        $kernel = $this->createMock(KernelInterface::class);

        $builder = $this->createMock(CronBuilder::class);

        $service = new CronService($bus, $repository, $aliasHelper, $kernel, $builder);

        $this->assertIsArray($service->resolve());
    }
}
