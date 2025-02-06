<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Util\CacheFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final readonly class InvalidateDoctrineCacheSubscriber implements EventSubscriberInterface
{
    public function __construct(private CacheFactory $cache)
    {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [['invalidate', 27]],
            ResponseEvent::class => [['invalidate', -27]],
        ];
    }

    public function invalidate(KernelEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (!$this->cache->isDisableQueryCache() && $event->getRequest()->isMethodCacheable()) {
            return;
        }

        $this->cache->invalidQueryCache();
    }
}
