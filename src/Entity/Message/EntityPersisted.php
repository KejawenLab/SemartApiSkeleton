<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity\Message;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class EntityPersisted
{
    private object $entity;

    public function __construct(object $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
