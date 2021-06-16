<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Audit;

use KejawenLab\ApiSkeleton\Audit\AuditItem;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class AuditItemTest extends TestCase
{
    public function testToArray(): void
    {
        $item = new AuditItem('test', ['data' => 'test data'], '2021-04-03', 'user-id', 'user-name', '127.0.0.1');
        $return = $item->toArray();

        $this->assertSame('test', $return['type']);
        $this->assertSame(['data' => 'test data'], $return['data']);
        $this->assertSame('2021-04-03', $return['log_time']);
        $this->assertSame('user-id', $return['user_id']);
        $this->assertSame('user-name', $return['username']);
        $this->assertSame('127.0.0.1', $return['ip_address']);
    }
}
