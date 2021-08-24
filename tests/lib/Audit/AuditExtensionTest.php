<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Audit;

use DH\Auditor\Provider\Doctrine\DoctrineProvider;
use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Audit\AuditExtension;
use KejawenLab\ApiSkeleton\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class AuditExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $reader = $this->createMock(Reader::class);

        $extension = new AuditExtension($reader);

        $this->assertSame(1, count(iterator_to_array($extension->getFunctions())));
    }

    public function testAuditable(): void
    {
        $provider = $this->createMock(DoctrineProvider::class);
        $provider->expects($this->once())->method('isAuditable')->willReturn(true);

        $reader = $this->createMock(Reader::class);
        $reader->expects($this->once())->method('getProvider')->willReturn($provider);

        $extension = new AuditExtension($reader);
        $auditable = new User();

        $this->assertTrue($extension->isAuditable($auditable));
    }
}
