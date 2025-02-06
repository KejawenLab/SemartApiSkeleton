<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements PaginatableRepositoryInterface
{
    protected const MICRO_CACHE = 1;

    public function __construct(
        protected RequestStack $requestStack,
        ManagerRegistry        $registry,
        string                 $entityClass,
    )
    {
        parent::__construct($registry, $entityClass);
    }

    #[\Override]
    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?object
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if ($deviceId !== '' && $deviceId !== '0') {
            $cacheLifetime = SemartApiSkeleton::QUERY_CACHE_LIFETIME;
        }

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.id', $queryBuilder->expr()->literal($id)));
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        if (!$this->isDisableCache()) {
            $query->useQueryCache(true);
            $query->enableResultCache($cacheLifetime, sprintf('%s_%s', $deviceId, $id));
        }

        return $query->getOneOrNullResult();
    }

    protected function getDeviceId(): string
    {
        try {
            $session = $this->requestStack->getSession();

            $deviceId = $session->get(SemartApiSkeleton::USER_DEVICE_ID, '');
            if (SemartApiSkeleton::API_CLIENT_DEVICE_ID === $deviceId) {
                return '';
            }

            return $deviceId;
        } catch (SessionNotFoundException) {
            return '';
        }
    }

    protected function isDisableCache(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            return false;
        }

        return !empty($request->query->get(SemartApiSkeleton::DISABLE_QUERY_CACHE_QUERY_STRING));
    }

    public function countRecords(): int
    {
        return is_countable($this->findAll()) ? \count($this->findAll()) : 0;
    }

    #[\Override]
    public function findAll(): array
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if ($deviceId !== '' && $deviceId !== '0') {
            $cacheLifetime = SemartApiSkeleton::QUERY_CACHE_LIFETIME;
        }

        $queryBuilder = $this->createQueryBuilder('o');
        $query = $queryBuilder->getQuery();
        if (!$this->isDisableCache()) {
            $query->useQueryCache(true);
            $query->enableResultCache($cacheLifetime, sprintf('%s_%s', $deviceId, sha1($query->getSQL())));
        }

        return $query->getResult();
    }

    public function persist(object $object): void
    {
        $this->getEntityManager()->persist($object);
    }

    public function remove(object $object): void
    {
        $this->getEntityManager()->remove($object);
        $this->getEntityManager()->flush();
    }

    public function commit(): void
    {
        $this->getEntityManager()->flush();
    }

    public function queryBuilder(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
