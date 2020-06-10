<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\EventSubscriber;

use Alpabit\ApiSkeleton\Security\Annotation\Parser;
use Alpabit\ApiSkeleton\Security\Authorization\Ownership;
use Alpabit\ApiSkeleton\Security\Service\Authorization;
use Alpabit\ApiSkeleton\Security\Service\PermissionService;
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

    public function validate(ControllerEvent $event): void
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

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'validate',
        ];
    }
}
