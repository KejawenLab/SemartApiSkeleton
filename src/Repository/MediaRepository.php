<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Repository;

use Alpabit\ApiSkeleton\Entity\Media;
use Alpabit\ApiSkeleton\Media\Model\MediaRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class MediaRepository extends AbstractRepository implements MediaRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }
}
