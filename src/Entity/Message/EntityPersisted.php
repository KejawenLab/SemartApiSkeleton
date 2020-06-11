<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Entity\Message;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
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
