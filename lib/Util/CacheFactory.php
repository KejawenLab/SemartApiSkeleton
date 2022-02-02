<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Util;

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

    public function invalidPageCache(): void
    {
        if (!($this->isDisablePageCache() || !$this->requestStack->getCurrentRequest()->isMethodCacheable())) {
            return;
        }

        $this->invalidateCache('page');
    }

    public function invalidViewCache(): void
    {
        if (!($this->isDisableViewCache() || !$this->requestStack->getCurrentRequest()->isMethodCacheable())) {
            return;
        }

        $this->invalidateCache('view');
    }

    public function getPageCache(): ?Response
    {
        if (!$this->requestStack->getCurrentRequest()->isMethodCacheable()) {
            return null;
        }

        if ($this->isDisablePageCache()) {
            return null;
        }

        $key = $this->getCacheKey();
        $data = $this->getCache($key, 'page');
        if (0 === count($data)) {
            return null;
        }

        if ('' === $data['content'] || false === $data['content']) {
            return null;
        }

        $response = new Response($data['content']);
        $response->headers->set('Content-Type', $data['attribute']);
        $response->headers->set(SemartApiSkeleton::CACHE_HEADER, $key);

        return $response;
    }

    public function setPageCache(Response $response): void
    {
        if (!$this->requestStack->getCurrentRequest()->isMethodCacheable()) {
            return;
        }

        if ($this->isDisablePageCache()) {
            return;
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return;
        }

        if ('' === $response->getContent() || false === $response->getContent()) {
            return;
        }

        $this->setCache($this->getCacheKey(), 'page', $response->getContent(), $response->headers->get('Content-Type'), SemartApiSkeleton::PAGE_CACHE_PERIOD);
    }

    public function getCache(string $key, string $namespace): array
    {
        $deviceId = $this->getDeviceId();
        if ('' === $deviceId) {
            return [];
        }

        $poolKey = sprintf('%s_%s', $deviceId, $namespace);
        $key = sprintf('%s_%s', $poolKey, $key);
        $pool = $this->cache->getItem($poolKey);
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

    public function setCache(string $key, string $namespace, string $content, string|bool $attribute, string $period): void
    {
        if ('' === $content) {
            return;
        }

        $deviceId = $this->getDeviceId();
        if ('' === $deviceId) {
            return;
        }

        $poolKey = sprintf('%s_%s', $deviceId, $namespace);
        $key = sprintf('%s_%s', $poolKey, $key);

        $pool = $this->cache->getItem($poolKey);
        $keys = [];
        if ($pool->isHit()) {
            $keys = $pool->get();
        }

        $keys = array_merge($keys, [$key => $attribute]);

        $pool->set($keys);
        $pool->expiresAfter(new DateInterval($period));
        $this->cache->save($pool);

        $item = $this->cache->getItem($key);

        $item->set($content);
        $item->expiresAfter(new DateInterval($period));
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

    public function invalidateCache(string $namespace): void
    {
        $deviceId = $this->getDeviceId();
        if ('' === $deviceId) {
            return;
        }

        $poolKey = sprintf('%s_%s', $deviceId, $namespace);
        $pool = $this->cache->getItem($poolKey);
        if (!$pool->isHit()) {
            return;
        }

        $keys = $pool->get();
        foreach ($keys as $key => $nothing) {
            $this->cache->deleteItem($key);
        }

        $this->cache->deleteItem($deviceId);
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

    private function isDisablePageCache(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request->query->get(SemartApiSkeleton::DISABLE_PAGE_CACHE_QUERY_STRING)) {
            return true;
        }

        if ($request->attributes->get(SemartApiSkeleton::DISABLE_PAGE_CACHE_QUERY_STRING)) {
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
