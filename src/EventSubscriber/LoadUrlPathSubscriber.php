<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use KejawenLab\Semart\ApiSkeleton\Security\Model\MenuInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class LoadUrlPathSubscriber implements EventSubscriber
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof MenuInterface) {
            $path = $object->getRouteName();
            try {
                $path = $this->urlGenerator->generate($object->getRouteName());
            } catch (RouteNotFoundException $exception) {
            }

            $object->setUrlPath($path);
        }
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
        ];
    }
}
