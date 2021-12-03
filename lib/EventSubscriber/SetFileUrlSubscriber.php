<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SetFileUrlSubscriber implements EventSubscriber
{
    public function __construct(private readonly StorageInterface $storage)
    {
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof MediaInterface) {
            $object->setFileUrl($this->storage->resolveUri($object, MediaInterface::FILE_FIELD));
        }
    }

    /**
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }
}
