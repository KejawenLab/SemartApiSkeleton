<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity\Message;

use KejawenLab\ApiSkeleton\Service\Message\EntityRemoved;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class EntityRemovedTest extends TestCase
{
    public function testGetEntity(): void
    {
        $object = new class {};

        $this->assertSame($object, (new EntityRemoved($object))->getEntity());
    }
}
