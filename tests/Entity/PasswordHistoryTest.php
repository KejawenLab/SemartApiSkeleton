<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Entity\PasswordHistory;
use KejawenLab\ApiSkeleton\Security\Model\PasswordHistoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class PasswordHistoryTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new PasswordHistory();

        $this->assertTrue($class instanceof PasswordHistoryInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
