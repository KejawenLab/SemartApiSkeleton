<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Audit;

use Alpabit\ApiSkeleton\Setting\SettingService;
use DH\DoctrineAuditBundle\Reader\AuditEntry;
use DH\DoctrineAuditBundle\Reader\AuditReader;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AuditService
{
    private AuditReader $auditReader;

    private int $cacheLifetime;

    private CacheItemPoolInterface $cache;

    public function __construct(AuditReader $auditReader, SettingService $setting, CacheItemPoolInterface $cache)
    {
        $this->auditReader = $auditReader;
        $this->cacheLifetime = (int) $setting->getSetting('CACHE_LIFETIME')->getValue();
        $this->cache = $cache;
    }

    public function getAudits(object $entity, string $id): Audit
    {
        $key = sha1(sprintf('%s_%s', get_class($entity), $id));
        $cache = $this->cache->getItem($key);
        if (!$cache->isHit()) {
            $audits = serialize($this->auditReader->getAudits(get_class($entity), $id, 1, 17));
            $cache->set($audits);
            $cache->expiresAfter($this->cacheLifetime);
            $this->cache->save($cache);
        }

        $audits = unserialize($cache->get());

        $record = new Audit($entity);
        /** @var AuditEntry[] $audits */
        foreach ($audits as $audit) {
            $record->addItem(new AuditItem($audit->getType(), $audit->getDiffs(), $audit->getUserId(), $audit->getUsername(), $audit->getIp()));
        }

        return $record;
    }
}
