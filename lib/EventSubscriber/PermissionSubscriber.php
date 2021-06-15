<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Security\Annotation\Parser;
use KejawenLab\ApiSkeleton\Security\Authorization\Ownership;
use KejawenLab\ApiSkeleton\Security\Service\Authorization;
use ReflectionObject;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PermissionSubscriber implements EventSubscriberInterface
{
    public function __construct(private Parser $parser, private Authorization $authorization, private Ownership $ownership)
    {
    }

    public function validate(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        /** @var object $controller */
        $controller = $event->getController();
        if (!is_object($controller)) {
            return;
        }

        $controllerReflection = new ReflectionObject($controller);
        $permission = $this->parser->parse($controllerReflection);
        if (!$permission) {
            return;
        }

        $namespaceArray = explode('\\', $controllerReflection->getNamespaceName());
        $namespace = array_pop($namespaceArray);
        $authorize = $this->authorization->authorize($permission);
        if (!$authorize) {
            throw new AccessDeniedException();
        }

        $id = $event->getRequest()->attributes->get('id');
        if ($permission->isOwnership() && $id && !$this->ownership->isOwner($id, $namespace)) {
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
