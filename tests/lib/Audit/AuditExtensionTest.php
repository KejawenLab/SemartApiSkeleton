<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Audit;

use KejawenLab\ApiSkeleton\Audit\AuditExtension;
use KejawenLab\ApiSkeleton\Cron\CronExtension;
use KejawenLab\ApiSkeleton\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class AuditExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $extension = new AuditExtension();

        $this->assertSame(1, count(iterator_to_array($extension->getFunctions())));
    }

    public function testAuditableIsTrue(): void
    {
        $extension = new AuditExtension();
        $auditable = new User();

        $this->assertTrue($extension->isAuditable($auditable));
    }

    public function testAuditableIsFalse(): void
    {
        $extension = new AuditExtension();
        $auditable = new User();

        $this->assertTrue($extension->isAuditable($auditable));
    }
}
