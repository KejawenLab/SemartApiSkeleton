<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Entity\Permission;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class PermissionTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new Permission();

        $this->assertTrue($class instanceof PermissionInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
