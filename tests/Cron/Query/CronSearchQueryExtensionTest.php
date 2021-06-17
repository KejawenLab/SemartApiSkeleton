<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Query;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Query\CronSearchQueryExtension;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronSearchQueryExtensionTest extends TestCase
{
    public function testSupport(): void
    {
        $queryExtension = new CronSearchQueryExtension(new AliasHelper());

        $request = Request::createFromGlobals();
        $request->query->set('q', 'fake');

        $cron = $this->createMock(CronInterface::class);

        $this->assertSame(false, $queryExtension->support($request::class, $request));
        $this->assertSame(false, $queryExtension->support($cron::class, Request::createFromGlobals()));
        $this->assertSame(true, $queryExtension->support($cron::class, $request));
    }

    public function testApply(): void
    {
        $queryExtension = new CronSearchQueryExtension(new AliasHelper());

        $request = Request::createFromGlobals();
        $request->query->set('q', 'fake');

        $queryExpr = new Expr();

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->exactly(4))->method('expr')->willReturn($queryExpr);

        $queryExtension->apply($queryBuilder, $request);
    }
}
