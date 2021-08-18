<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\ApiClient\Query;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Query\FilterByUserExtension;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class FilterByUserExtensionTest extends TestCase
{
    private AliasHelper $aliasHelper;

    public function setUp(): void
    {
        $this->aliasHelper = new AliasHelper();
    }

    public function testApply(): void
    {
        $filter = new FilterByUserExtension($this->aliasHelper);
        $queryExpr = new Expr();

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->exactly(2))->method('expr')->willReturn($queryExpr);

        $request = Request::createFromGlobals();
        $request->attributes->set('userId', 'c6248158-18f2-4e89-9279-74e3e0c6d87f');

        $filter->apply($queryBuilder, $request);
    }

    public function testSupport(): void
    {
        $filter = new FilterByUserExtension($this->aliasHelper);

        $this->assertSame(true, $filter->support($this->createMock(ApiClientInterface::class)::class, Request::createFromGlobals()));
    }
}
