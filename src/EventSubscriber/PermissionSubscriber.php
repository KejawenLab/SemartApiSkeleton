<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Security\Annotation\Parser;
use App\Security\Authorization\Ownership;
use App\Security\Service\Authorization;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PermissionSubscriber implements EventSubscriberInterface
{
    private $parser;

    private $authorization;

    private $ownership;

    public function __construct(Parser $parser, Authorization $authorization, Ownership $ownership)
    {
        $this->parser = $parser;
        $this->authorization = $authorization;
        $this->ownership = $ownership;
    }

    public function validate(ControllerEvent $event)
    {
        $controllerReflection = new \ReflectionObject($event->getController());
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
            ControllerEvent::class => 'validate',
        ];
    }
}
