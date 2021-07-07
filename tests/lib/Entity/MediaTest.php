<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class MediaTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new Media();

        $this->assertTrue($class instanceof MediaInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
