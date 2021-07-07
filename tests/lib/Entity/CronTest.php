<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class CronTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new Cron();

        $this->assertTrue($class instanceof CronInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
