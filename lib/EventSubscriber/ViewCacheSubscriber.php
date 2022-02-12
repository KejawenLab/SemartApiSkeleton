<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Util\CacheFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class ViewCacheSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly CacheFactory $cache)
    {
    }

    public function serve(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $this->cache->getPageCache();
        if (!$response instanceof Response) {
            return;
        }

        $event->setResponse($response);
        $event->stopPropagation();
    }

    public function invalidate(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->cache->invalidPageCache();
        $this->cache->invalidViewCache();
    }

    public function populate(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->cache->setPageCache($event->getResponse());
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [
                ['serve', 125],
                ['invalidate', 17],
            ],
            ResponseEvent::class => [['populate', -27]],
        ];
    }
}
