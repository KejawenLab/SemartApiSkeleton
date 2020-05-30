<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\EventSubscriber;

use KejawenLab\Semart\ApiSkeleton\Entity\Event\PersistEvent;
use KejawenLab\Semart\ApiSkeleton\Entity\Event\RemoveEvent;
use KejawenLab\Semart\ApiSkeleton\Security\Annotation\Parser;
use KejawenLab\Semart\ApiSkeleton\Security\Authorization\Ownership;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionableInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Service\Authorization;
use KejawenLab\Semart\ApiSkeleton\Security\Service\PermissionService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PermissionSubscriber implements EventSubscriberInterface
{
    private $service;

    private $parser;

    private $authorization;

    private $ownership;

    public function __construct(PermissionService $service, Parser $parser, Authorization $authorization, Ownership $ownership)
    {
        $this->service = $service;
        $this->parser = $parser;
        $this->authorization = $authorization;
        $this->ownership = $ownership;
    }

    public function initiate(PersistEvent $event): void
    {
        $object = $event->getEntity();
        if ($object instanceof PermissionableInterface) {
            $event->getManager()->getFilters()->disable(PermissionService::FILTER_NAME);
            $this->service->initiate($object);
            $event->getManager()->getFilters()->enable(PermissionService::FILTER_NAME);
        }
    }

    public function revoke(RemoveEvent $event): void
    {
        $object = $event->getEntity();
        if ($object instanceof PermissionableInterface) {
            $this->service->revoke($object);
        }
    }

    public function validate(ControllerEvent $event)
    {
        /** @var object $controller */
        $controller = $event->getController();
        if (!is_object($controller)) {
            return;
        }

        $controllerReflection = new \ReflectionObject($controller);
        $permission = $this->parser->parse($controllerReflection);
        if (!$permission) {
            return;
        }

        $authorize = $this->authorization->authorize($permission);
        if (!$authorize) {
            throw new AccessDeniedException();
        }

        if ($permission->isOwnership() && !$this->ownership->isOwner($event->getRequest())) {
            throw new AccessDeniedException();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            PersistEvent::class => 'initiate',
            RemoveEvent::class => 'revoke',
            ControllerEvent::class => 'validate',
        ];
    }
}
