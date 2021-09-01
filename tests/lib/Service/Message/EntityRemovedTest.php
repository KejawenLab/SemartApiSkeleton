<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Service\Message;

use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Service\Message\EntityRemoved;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class EntityRemovedTest extends TestCase
{
    public function testPassingObject(): void
    {
        $entity = new User();

        $message = new EntityRemoved($entity);

        $this->assertSame($entity, $message->getEntity());
    }
}
