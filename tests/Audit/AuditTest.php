<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Audit;

use KejawenLab\ApiSkeleton\Audit\Audit;
use KejawenLab\ApiSkeleton\Audit\AuditItem;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class AuditTest extends TestCase
{
    public function testAddItem(): void
    {
        $audit = new Audit($this->createMock(EntityInterface::class));
        $audit->addItem(new AuditItem('test', ['data' => 'test data'], '2021-04-03', 'user-id', 'user-name', '127.0.0.1'));

        $this->assertSame(Audit::class, $audit::class);
    }

    public function testToArray(): void
    {
        $entity = $this->createMock(EntityInterface::class);

        $audit = new Audit($entity);
        $audit->addItem(new AuditItem('test', ['data' => 'test data'], '2021-04-03', 'user-id', 'user-name', '127.0.0.1'));

        $return = $audit->toArray();

        $this->assertSame($entity, $return['entity']);
        $this->assertSame('test', $return['items'][0]['type']);
        $this->assertSame(['data' => 'test data'], $return['items'][0]['data']);
        $this->assertSame('2021-04-03', $return['items'][0]['log_time']);
        $this->assertSame('user-id', $return['items'][0]['user_id']);
        $this->assertSame('user-name', $return['items'][0]['username']);
        $this->assertSame('127.0.0.1', $return['items'][0]['ip_address']);
    }
}
