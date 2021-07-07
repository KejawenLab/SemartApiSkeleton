<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class UserTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new User();

        $this->assertTrue($class instanceof UserInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
