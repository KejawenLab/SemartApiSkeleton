<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Security\Annotation\Parser;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Authorization\Ownership;
use KejawenLab\ApiSkeleton\Security\Service\Authorization;
use ReflectionObject;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PermissionSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly Parser $parser, private readonly Authorization $authorization, private readonly Ownership $ownership)
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
        if (!$permission instanceof Permission) {
            return;
        }

        $namespaceArray = explode('\\', $controllerReflection->getNamespaceName());
        $entity = array_pop($namespaceArray);
        $authorize = $this->authorization->authorize($permission);
        if (!$authorize) {
            throw new AccessDeniedException();
        }

        $id = $event->getRequest()->attributes->get('id');
        if (!$permission->isOwnership()) {
            return;
        }

        if (!$id) {
            return;
        }

        if ($this->ownership->isOwner($id, $entity)) {
            return;
        }

        throw new AccessDeniedException();
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'validate',
        ];
    }
}
