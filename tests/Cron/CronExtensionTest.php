<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron;

use KejawenLab\ApiSkeleton\Cron\CronExtension;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $extension = new CronExtension();

        $this->assertSame(1, count(iterator_to_array($extension->getFunctions())));
    }

    public function testNormalize(): void
    {
        $extension = new CronExtension();
        $log = sprintf('A%sB%sC%s', PHP_EOL, PHP_EOL, PHP_EOL);

        $this->assertSame(['A', 'B', 'C'], $extension->normalize($log));
    }
}
