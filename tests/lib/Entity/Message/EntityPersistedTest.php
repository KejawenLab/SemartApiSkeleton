<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity\Message;

use KejawenLab\ApiSkeleton\Service\Message\EntityPersisted;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class EntityPersistedTest extends TestCase
{
    public function testGetEntity(): void
    {
        $object = new class {};

        $this->assertSame($object, (new EntityPersisted($object))->getEntity());
    }
}
