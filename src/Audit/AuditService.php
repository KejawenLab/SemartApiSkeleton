<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

use DH\Auditor\Model\Entry;
use DH\Auditor\Provider\Doctrine\Persistence\Reader\Query;
use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AuditService
{
    private Reader $auditReader;

    private int $cacheLifetime;

    private CacheItemPoolInterface $cache;

    public function __construct(Reader $auditReader, SettingService $setting, CacheItemPoolInterface $cache)
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
            $audits = serialize($this->auditReader->createQuery(get_class($entity), ['page' => 1, 'page_size' => 9])->addFilter(Query::OBJECT_ID, $id)->execute());
            $cache->set($audits);
            $cache->expiresAfter($this->cacheLifetime);
            $this->cache->save($cache);
        }

        $audits = unserialize($cache->get());

        $record = new Audit($entity);
        /** @var Entry[] $audits */
        foreach ($audits as $audit) {
            $record->addItem(new AuditItem($audit->getType(), $audit->getDiffs(), $audit->getCreatedAt(), $audit->getUserId(), $audit->getUsername(), $audit->getIp()));
        }

        return $record;
    }
}
