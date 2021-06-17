<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Query;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Cron\Model\CronReportInterface;
use KejawenLab\ApiSkeleton\Cron\Query\CronReportSearchQueryExtension;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronReportSearchQueryExtensionTest extends TestCase
{
    public function testSupport(): void
    {
        $queryExtension = new CronReportSearchQueryExtension(new AliasHelper());

        $report = $this->createMock(CronReportInterface::class);

        $this->assertSame(true, $queryExtension->support($report::class, Request::createFromGlobals()));
    }

    public function testApply(): void
    {
        $queryExtension = new CronReportSearchQueryExtension(new AliasHelper());

        $request = Request::createFromGlobals();
        $request->query->set('q', 'fake');

        $queryExpr = new Expr();

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->exactly(2))->method('expr')->willReturn($queryExpr);

        $queryExtension->apply($queryBuilder, $request);
    }
}
