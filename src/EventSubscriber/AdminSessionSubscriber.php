<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AdminSessionSubscriber implements EventSubscriberInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function validate(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!AdminContext::isAdminContext($request)) {
            return;
        }

        if (!AdminContext::isAdminLoggedIn($request) && $request->query->get(AdminContext::ADMIN_ACTION_KEY) !== AdminContext::ADMIN_LOGIN_ACTION) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate(AdminContext::ADMIN_LOGIN_ROUTE_NAME)));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [['validate', 7]],
        ];
    }
}
