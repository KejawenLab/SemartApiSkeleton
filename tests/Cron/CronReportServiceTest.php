<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Cron\CronReportService;
use KejawenLab\ApiSkeleton\Cron\Model\CronReportRepositoryInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class CronReportServiceTest extends TestCase
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testCountStale(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);

        $queryExpr = new Expr();

        $query = $this->createMock(Query::class);
        $query->expects($this->once())->method('getSingleScalarResult')->willReturn(1);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->exactly(2))->method('expr')->willReturn($queryExpr);
        $queryBuilder->expects($this->once())->method('getQuery')->willReturn($query);

        $repository = $this->createMock(CronReportRepositoryInterface::class);
        $repository->expects($this->once())->method('queryBuilder')->withAnyParameters()->willReturn($queryBuilder);

        $service = new CronReportService($bus, $repository, new AliasHelper());
        $this->assertSame(1, $service->countStale());
    }

    public function testClean(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);

        $queryExpr = new Expr();

        $query = $this->createMock(Query::class);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->once())->method('delete')->willReturn($queryBuilder);
        $queryBuilder->expects($this->exactly(2))->method('expr')->willReturn($queryExpr);
        $queryBuilder->expects($this->once())->method('getQuery')->willReturn($query);

        $repository = $this->createMock(CronReportRepositoryInterface::class);
        $repository->expects($this->once())->method('queryBuilder')->withAnyParameters()->willReturn($queryBuilder);

        $service = new CronReportService($bus, $repository, new AliasHelper());
        $service->clean();
    }
}
