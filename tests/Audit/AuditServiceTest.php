<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Audit;

use DH\Auditor\Model\Entry;
use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Audit\Audit;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class AuditServiceTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     */
    public function testGetAudits(): void
    {
        $reader = $this->createMock(Reader::class);
        $setting = $this->createMock(SettingService::class);

        $entry = $this->createMock(Entry::class);
        $entry->method('getType')->willReturn('INSERT');
        $entry->method('getCreatedAt')->willReturn('2021-04-03');
        $entry->method('getDiffs')->willReturn([]);

        $item = $this->createMock(CacheItemInterface::class);
        $item->expects($this->once())->method('get')->willReturn(serialize([$entry]));

        $cache = $this->createMock(CacheItemPoolInterface::class);
        $cache->expects($this->once())->method('getItem')->withAnyParameters()->willReturn($item);

        $service = new AuditService($reader, $setting, $cache);

        $id = '4e435184-a0a7-4848-8381-82abe0dbd752';
        $entity = $this->createMock(EntityInterface::class);

        $this->assertSame(Audit::class, $service->getAudits($entity, $id)::class);
    }
}
