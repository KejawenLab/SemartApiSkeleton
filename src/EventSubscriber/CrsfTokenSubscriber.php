<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CrsfTokenSubscriber implements EventSubscriberInterface
{
    public function validate(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (AdminContext::ADMIN_LOGIN_ACTION === $request->query->get(AdminContext::ADMIN_ACTION_KEY) && $request->isMethod(Request::METHOD_POST)) {
            $session = $request->getSession();
            if ($session->get(AdminContext::ADMIN_CRSF_SESSION_KEY) !== $request->request->get(AdminContext::ADMIN_CRSF_REQUEST_KEY)) {
                dump($session->get(AdminContext::ADMIN_CRSF_SESSION_KEY));
                dump($request->request->get(AdminContext::ADMIN_CRSF_REQUEST_KEY));
                throw new InvalidCsrfTokenException();
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'validate',
        ];
    }
}
