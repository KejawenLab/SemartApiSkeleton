<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Media\Model\MediaRepositoryInterface;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class MediaRepository extends AbstractRepository implements MediaRepositoryInterface
{
    public function __construct(RequestStack $requestStack, ManagerRegistry $registry)
    {
        parent::__construct($requestStack, $registry, Media::class);
    }

    public function findByFilename(string $fileName, string $folder = null): ?MediaInterface
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if (!empty($deviceId)) {
            $cacheLifetime = SemartApiSkeleton::QUERY_CACHE_LIFETIME;
        }

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.fileName', $queryBuilder->expr()->literal($fileName)));
        if (null !== $folder) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('o.folder', $queryBuilder->expr()->literal($folder)));
        }

        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        if (!$this->isDisableCache()) {
            $query->useQueryCache(true);
            $query->enableResultCache($cacheLifetime, sprintf('%s_%s_%s_%s_%s', $deviceId, sha1(self::class), sha1(__METHOD__), sha1($folder), sha1($fileName)));
        }

        return $query->getOneOrNullResult();
    }
}
