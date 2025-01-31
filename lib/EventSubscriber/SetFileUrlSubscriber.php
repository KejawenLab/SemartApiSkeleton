<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\ApiSkeleton\Entity\Media as Entity;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[AsEntityListener(event: Events::postLoad, method: 'postLoad', entity: Entity::class)]
final class SetFileUrlSubscriber
{
    public function __construct(private readonly StorageInterface $storage)
    {
    }

    public function postLoad(Entity $object, PostLoadEventArgs $args): void
    {
        $object->setFileUrl($this->storage->resolveUri($object, MediaInterface::FILE_FIELD));
    }
}
