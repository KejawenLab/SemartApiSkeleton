<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Service\Message;

use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Service\Message\EntityPersisted;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class EntityPersistedTest extends TestCase
{
    public function testPassingObject(): void
    {
        $entity = new User();

        $message = new EntityPersisted($entity);

        $this->assertSame($entity, $message->getEntity());
    }
}
