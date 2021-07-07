<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class MenuTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new Menu();

        $this->assertTrue($class instanceof MenuInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
