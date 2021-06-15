<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClientRequest;

/**
 * @method ApiClientRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiClientRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiClientRequest[]    findAll()
 * @method ApiClientRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ApiClientRequestRepository extends AbstractRepository implements ApiClientRequestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiClientRequest::class);
    }
}
