<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class GroupTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new Group();

        $this->assertTrue($class instanceof GroupInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
