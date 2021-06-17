<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron;

use KejawenLab\ApiSkeleton\Cron\CronBuilder;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $kernel = $this->createMock(KernelInterface::class);
        $kernel->expects($this->any())->method('getEnvironment')->willReturn('test');

        $nonSymfony = $this->createMock(CronInterface::class);
        $nonSymfony->expects($this->once())->method('isSymfonyCommand')->willReturn(false);
        $nonSymfony->expects($this->once())->method('getCommand')->willReturn('ls');

        $symfony = $this->createMock(CronInterface::class);
        $symfony->expects($this->once())->method('isSymfonyCommand')->willReturn(true);
        $symfony->expects($this->once())->method('getCommand')->willReturn('debug:router');

        $builder = new CronBuilder($kernel);

        $docRoot = explode('/', $_SERVER['NGINX_WEBROOT']);
        array_pop($docRoot);

        $this->assertSame('ls', $builder->build($nonSymfony));
        $this->assertSame(sprintf('%s %s/bin/console debug:router --env=test', (new PhpExecutableFinder())->find(), implode('/', $docRoot)), $builder->build($symfony));
    }
}
