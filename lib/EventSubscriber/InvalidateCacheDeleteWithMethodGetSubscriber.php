<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class InvalidateCacheDeleteWithMethodGetSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly CacheItemPoolInterface $cache, private readonly EntityManagerInterface $entityManager)
    {
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

        $configuration = $this->entityManager->getConfiguration();

        $configuration->getQueryCache()->clear();
        $configuration->getResultCache()->clear();

        $deviceId = $this->getDeviceId($event);
        if (empty($deviceId)) {
            return;
        }

        $pool = $this->cache->getItem($deviceId);
        if (!$pool->isHit()) {
            return;
        }

        $keys = $pool->get();
        foreach ($keys as $key => $nothing) {
            $this->cache->deleteItem($key);
        }

        $this->cache->deleteItem($deviceId);
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

    private function getDeviceId(RequestEvent $event): string
    {
        $deviceId = $event->getRequest()->getSession()->get(SemartApiSkeleton::USER_DEVICE_ID, '');
        if ($deviceId === ApiClientInterface::DEVICE_ID) {
            return '';
        }

        return $deviceId;
    }
}
