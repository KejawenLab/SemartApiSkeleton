<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cache;

use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CacheFactory
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly CacheItemPoolInterface $cache,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function invalidQueryCache(): void
    {
        if (!($this->isDisableQueryCache() || !$this->requestStack->getCurrentRequest()->isMethodCacheable())) {
            return;
        }

        $configuration = $this->entityManager->getConfiguration();

        $configuration->getQueryCache()->clear();
        $configuration->getResultCache()->clear();
    }

    public function invalidPageAndViewCache(): void
    {
        if (!($this->isDisablePageOrViewCache() || !$this->requestStack->getCurrentRequest()->isMethodCacheable())) {
            return;
        }

        $deviceId = $this->getDeviceId();
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

    public function getPageCache(): ?Response
    {
        if (!$this->requestStack->getCurrentRequest()->isMethodCacheable()) {
            return null;
        }

        if ($this->isDisablePageOrViewCache()) {
            return null;
        }

        $key = $this->getCacheKey();
        $data = $this->getCache($key);
        if (0 === count($data)) {
            return null;
        }

        $response = new Response($data['content']);
        $response->headers->set('Content-Type', $data['attribute']);
        $response->headers->set(SemartApiSkeleton::STATIC_CACHE_HEADER, $key);

        return $response;
    }

    public function setPageCache(Response $response): void
    {
        if (!$this->requestStack->getCurrentRequest()->isMethodCacheable()) {
            return;
        }

        if ($this->isDisablePageOrViewCache()) {
            return;
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return;
        }

        $this->setCache($this->getCacheKey(), $response->getContent(), $response->headers->get('Content-Type'));
    }

    public function getCache(string $key): array
    {
        $deviceId = $this->getDeviceId();
        if ('' === $deviceId) {
            return [];
        }

        $key = sprintf('%s_%s', $deviceId, $key);
        $pool = $this->cache->getItem($deviceId);
        if (!$pool->isHit()) {
            return [];
        }

        $keys = $pool->get();
        if (!array_key_exists($key, $keys)) {
            return [];
        }

        return [
            'content' => $this->cache->getItem($key)->get(),
            'attribute' => $keys[$key],
        ];
    }

    public function setCache(string $key, string $content, string|bool $attribute): void
    {
        $deviceId = $this->getDeviceId();
        if ('' === $deviceId) {
            return;
        }

        $key = sprintf('%s_%s', $deviceId, $key);
        $item = $this->cache->getItem($key);

        $pool = $this->cache->getItem($deviceId);
        $keys = [];
        if ($pool->isHit()) {
            $keys = $pool->get();
        }

        $keys = array_merge($keys, [$key => $attribute]);

        $pool->set($keys);
        $pool->expiresAfter(new DateInterval(SemartApiSkeleton::STATIC_PAGE_CACHE_PERIOD));
        $this->cache->save($pool);

        $item->set($content);
        $item->expiresAfter(new DateInterval(SemartApiSkeleton::STATIC_PAGE_CACHE_PERIOD));
        $this->cache->save($item);
    }

    public function isDisableViewCache(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request->query->get(SemartApiSkeleton::DISABLE_VIEW_CACHE_QUERY_STRING)) {
            return true;
        }

        if ($request->attributes->get(SemartApiSkeleton::DISABLE_VIEW_CACHE_QUERY_STRING)) {
            return true;
        }

        return false;
    }

    private function getCacheKey(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $flashBag = $request->getSession()->getFlashBag();
        $flashes = $flashBag->get('id');
        $id = '';
        foreach ($flashes as $flash) {
            $id = $flash;

            break;
        }

        if ('' !== $id) {
            $flashBag->set('id', $id);

            return '';
        }

        return sprintf('%s_%s', sha1($request->getPathInfo()), sha1(serialize($request->query->all())));
    }

    private function isDisablePageOrViewCache(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request->query->get(SemartApiSkeleton::DISABLE_PAGE_CACHE_QUERY_STRING)) {
            return true;
        }

        if ($request->attributes->get(SemartApiSkeleton::DISABLE_PAGE_CACHE_QUERY_STRING)) {
            return true;
        }

        if ($request->query->get(SemartApiSkeleton::DISABLE_VIEW_CACHE_QUERY_STRING)) {
            return true;
        }

        if ($request->attributes->get(SemartApiSkeleton::DISABLE_VIEW_CACHE_QUERY_STRING)) {
            return true;
        }

        return false;
    }

    private function isDisableQueryCache(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request->query->get(SemartApiSkeleton::DISABLE_QUERY_CACHE_QUERY_STRING)) {
            return true;
        }

        if ($request->attributes->get(SemartApiSkeleton::DISABLE_QUERY_CACHE_QUERY_STRING)) {
            return true;
        }

        return false;
    }

    private function getDeviceId(): string
    {
        $deviceId = $this->requestStack->getCurrentRequest()->getSession()->get(SemartApiSkeleton::USER_DEVICE_ID, '');
        if ($deviceId === SemartApiSkeleton::API_CLIENT_DEVICE_ID) {
            return '';
        }

        return $deviceId;
    }
}
