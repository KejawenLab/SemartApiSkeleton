<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Service\Message;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
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
