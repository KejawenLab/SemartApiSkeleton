<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\Util\CacheFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class InvalidateCacheDeleteWithMethodGetSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly CacheFactory $cache)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [['invalidate', 127]],
        ];
    }

    public function invalidate(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!AdminContext::isAdminContext($request)) {
            return;
        }

        if (!str_ends_with($request->getPathInfo(), '/delete')) {
            return;
        }

        if (!$request->isMethod(Request::METHOD_GET)) {
            return;
        }

        $this->cache->invalidQueryCache();
    }
}
