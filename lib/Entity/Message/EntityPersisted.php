<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity\Message;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class EntityPersisted
{
    public function __construct(private object $entity)
    {
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
