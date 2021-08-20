<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Validator;

use KejawenLab\ApiSkeleton\Cron\Validator\CronScheduleFormat;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class CronScheduleFormatTest extends TestCase
{
    public function testGetMessage(): void
    {
        $this->assertSame('sas.validator.cron.format_not_valid', (new CronScheduleFormat())->getMessage());
    }

    public function testInstance(): void
    {
        $this->assertSame(CronScheduleFormat::class, (new CronScheduleFormat())::class);
    }
}
