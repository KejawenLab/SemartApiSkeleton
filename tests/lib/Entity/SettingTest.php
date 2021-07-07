<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class SettingTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new Setting();

        $this->assertTrue($class instanceof SettingInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
