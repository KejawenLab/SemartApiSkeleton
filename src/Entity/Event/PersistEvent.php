<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Entity\Event;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class PersistEvent extends Event
{
    private $manager;

    private $entity;

    public function __construct(EntityManagerInterface $entityManager, object $entity)
    {
        $this->manager = $entityManager;
        $this->entity = $entity;
    }

    public function getManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
