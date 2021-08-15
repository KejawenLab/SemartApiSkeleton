<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

use DH\Auditor\Model\Entry;
use DH\Auditor\Provider\Doctrine\Persistence\Reader\Query;
use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AuditService
{
    private int $cacheLifetime;

    public function __construct(private Reader $auditReader, SettingService $setting, private CacheItemPoolInterface $cache)
    {
        $this->cacheLifetime = $setting->getCacheLifetime();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getAudits(object $entity, string $id, int $limit = 9): Audit
    {
        $key = sha1(sprintf('%s_%s', $entity::class, $id));
        $cache = $this->cache->getItem($key);
        if (!$cache->isHit()) {
            $audits = serialize(
                $this->auditReader->createQuery(
                    $entity::class,
                    ['page' => 1, 'page_size' => $limit]
                )
                ->addFilter(Query::OBJECT_ID, $id)->execute()
            );
            $cache->set($audits);
            $cache->expiresAfter($this->cacheLifetime);
            $this->cache->save($cache);
        }

        /** @var Entry[] $audits */
        $audits = unserialize($cache->get());

        $record = new Audit($entity);
        foreach ($audits as $audit) {
            $record->addItem(new AuditItem(
                $audit->getType(),
                $audit->getDiffs(),
                $audit->getCreatedAt(),
                $audit->getUserId(),
                $audit->getUsername(),
                $audit->getIp()
            ));
        }

        return $record;
    }
}
