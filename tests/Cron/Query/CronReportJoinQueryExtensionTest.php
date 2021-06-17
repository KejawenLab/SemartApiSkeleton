<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Query;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Cron\Model\CronReportInterface;
use KejawenLab\ApiSkeleton\Cron\Query\CronReportJoinExtension;
use KejawenLab\ApiSkeleton\Cron\Query\CronReportSearchQueryExtension;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronReportJoinQueryExtensionTest extends TestCase
{
    public function testSupport(): void
    {
        $queryExtension = new CronReportJoinExtension(new AliasHelper());

        $report = $this->createMock(CronReportInterface::class);

        $this->assertSame(true, $queryExtension->support($report::class, Request::createFromGlobals()));
    }

    public function testApply(): void
    {
        $queryExtension = new CronReportJoinExtension(new AliasHelper());

        $request = Request::createFromGlobals();
        $request->attributes->set('id', Uuid::uuid4()->toString());

        $queryExpr = new Expr();

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->exactly(2))->method('expr')->willReturn($queryExpr);

        $queryExtension->apply($queryBuilder, $request);
    }
}
