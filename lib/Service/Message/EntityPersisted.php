<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Service\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[AsMessage('sync')]
class EntityPersisted
{
    public function __construct(private readonly object $entity)
    {
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
