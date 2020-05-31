<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\EventSubscriber;

use Alpabit\ApiSkeleton\Entity\Event\PersistEvent;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Security\Service\PasswordEncoder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class EncodePasswordSubscriber implements EventSubscriberInterface
{
    private $encoder;

    public function __construct(PasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encode(PersistEvent $event): void
    {
        $entity = $event->getEntity();
        if (!$entity instanceof UserInterface) {
            return;
        }

        $this->encoder->encode($entity);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PersistEvent::class => 'encode',
        ];
    }
}
