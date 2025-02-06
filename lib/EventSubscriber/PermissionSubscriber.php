<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

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
final readonly class PermissionSubscriber implements EventSubscriberInterface
{
    public function __construct(private Authorization $authorization, private Ownership $ownership)
    {
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

    public function validate(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        /** @var object $controller */
        $controller = $event->getController();
        if (!\is_object($controller)) {
            return;
        }

        $attributes = $event->getAttributes();
        $permission = $attributes[Permission::class] ?? null;
        if (!$permission) {
            return;
        }

        $namespaceArray = explode('\\', new ReflectionObject($controller)->getNamespaceName());
        $entity = array_pop($namespaceArray);
        $authorize = $this->authorization->authorize($permission[0]);
        if (!$authorize) {
            throw new AccessDeniedException();
        }

        $id = $event->getRequest()->attributes->get('id');
        if (!$permission[0]->isOwnership()) {
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
}
