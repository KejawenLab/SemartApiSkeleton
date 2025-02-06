<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting;

use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\Model\SettingRepositoryInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SettingService extends AbstractService implements ServiceInterface
{
    public function __construct(
        MessageBusInterface                     $messageBus,
        SettingRepositoryInterface              $repository,
        AliasHelper                             $aliasHelper,
        private readonly SettingGroupFactory    $groupFactory,
        private readonly CacheItemPoolInterface $cache,
    )
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    /**
     * @return array<string>
     */
    public function getGroups(): array
    {
        return $this->groupFactory->getGroups();
    }

    public function getPublicSetting(string $id): ?SettingInterface
    {
        return $this->repository->findPublicSetting($id);
    }

    public function getCacheLifetime(): int
    {
        $cache = $this->cache->getItem(SettingInterface::CACHE_ID_CACHE_LIFETIME);
        if ($cache->isHit()) {
            return $cache->get();
        }

        $setting = (int)$this->getSetting('CACHE_LIFETIME')->getValue();

        $cache->set($setting);
        $cache->expiresAfter(SemartApiSkeleton::QUERY_CACHE_LIFETIME);
        $this->cache->save($cache);

        return $setting;
    }

    public function getSetting(string $key): SettingInterface
    {
        if ($setting = $this->repository->findByParameter($key)) {
            return $setting;
        }

        throw new SettingNotFoundException();
    }

    public function getPageField(): ?string
    {
        $cache = $this->cache->getItem(SettingInterface::CACHE_ID_PAGE_FIELD);
        if ($cache->isHit()) {
            return $cache->get();
        }

        $setting = $this->getSetting('PAGE_FIELD')->getValue();

        $cache->set($setting);
        $cache->expiresAfter(SemartApiSkeleton::QUERY_CACHE_LIFETIME);
        $this->cache->save($cache);

        return $setting;
    }

    public function getPerPageField(): ?string
    {
        $cache = $this->cache->getItem(SettingInterface::CACHE_ID_PER_PAGE_FIELD);
        if ($cache->isHit()) {
            return $cache->get();
        }

        $setting = $this->getSetting('PER_PAGE_FIELD')->getValue();

        $cache->set($setting);
        $cache->expiresAfter(SemartApiSkeleton::QUERY_CACHE_LIFETIME);
        $this->cache->save($cache);

        return $setting;
    }

    public function getRecordPerPage(): int
    {
        $cache = $this->cache->getItem(SettingInterface::CACHE_ID_PER_PAGE);
        if ($cache->isHit()) {
            return $cache->get();
        }

        $setting = (int)$this->getSetting('PER_PAGE')->getValue();

        $cache->set($setting);
        $cache->expiresAfter(SemartApiSkeleton::QUERY_CACHE_LIFETIME);
        $this->cache->save($cache);

        return $setting;
    }

    public function getMaxApiPerUser(): int
    {
        $cache = $this->cache->getItem(SettingInterface::CACHE_ID_MAX_API_PER_USER);
        if ($cache->isHit()) {
            return $cache->get();
        }

        $setting = (int)$this->getSetting('MAX_API_PER_USER')->getValue();

        $cache->set($setting);
        $cache->expiresAfter(SemartApiSkeleton::QUERY_CACHE_LIFETIME);
        $this->cache->save($cache);

        return $setting;
    }
}
