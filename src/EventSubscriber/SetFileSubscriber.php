<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\EventSubscriber;

use Alpabit\ApiSkeleton\Entity\Event\PersistEvent;
use Alpabit\ApiSkeleton\Media\Model\MediaInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SetFileSubscriber implements EventSubscriberInterface
{
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function setFile(PersistEvent $event): void
    {
        $object = $event->getEntity();
        if (!$object instanceof MediaInterface) {
            return;
        }

        if (!$file = $this->request->files->get('file')) {
            throw new BadRequestException(sprintf('Field "file" can not blank'));
        }

        $object->setFile($file);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PersistEvent::class => 'setFile',
        ];
    }
}
