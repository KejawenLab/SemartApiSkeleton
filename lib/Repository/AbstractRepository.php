<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
        ManagerRegistry $registry,
        string $entityClass,
    ) {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @return mixed|null
     *
     * @throws NonUniqueResultException
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if (!empty($deviceId)) {
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

    public function findAll(): iterable
    {
        $deviceId = $this->getDeviceId();
        $cacheLifetime = self::MICRO_CACHE;
        if (!empty($deviceId)) {
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

    public function countRecords(): int
    {
        return is_countable($this->findAll()) ? \count($this->findAll()) : 0;
    }

    public function persist(object $object): void
    {
        $this->_em->persist($object);
    }

    public function remove(object $object): void
    {
        $this->_em->remove($object);
    }

    public function commit(): void
    {
        $this->_em->flush();
    }

    public function queryBuilder(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }

    protected function isDisableCache(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            return false;
        }

        return !empty($request->query->get(SemartApiSkeleton::DISABLE_QUERY_CACHE_QUERY_STRING));
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
}
