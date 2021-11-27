<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Media\Model\MediaRepositoryInterface;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    public function findByFilename(string $fileName, string $folder = null): ?MediaInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.fileName', $queryBuilder->expr()->literal($fileName)));
        if (null !== $folder) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('o.folder', $queryBuilder->expr()->literal($folder)));
        }

        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->enableResultCache(self::MICRO_CACHE, sprintf(
            '%s_%s_%s_%s',
            str_replace([':', '/', '\\'], "_", self::class),
            str_replace([':', '/', '\\'], "_", __METHOD__),
            str_replace([':', '/', '\\'], "_", $fileName),
            str_replace([':', '/', '\\'], "_", $folder),
        ));

        return $query->getOneOrNullResult();
    }
}
