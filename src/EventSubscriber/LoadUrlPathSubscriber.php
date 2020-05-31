<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\EventSubscriber;

use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
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
                if ('#' !== $path) {
                    $path = $this->urlGenerator->generate($object->getRouteName());
                }
            } catch (RouteNotFoundException $exception) {
                $path = $this->urlGenerator->generate(sprintf('%s__invoke', $object->getRouteName()));
            }

            $object->setUrlPath($path);
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }
}
