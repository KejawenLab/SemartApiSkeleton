<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\ApiClient\Query;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Query\FilterByUserExtension;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class FilterByUserExtensionTest extends TestCase
{
    private TokenStorageInterface $tokenStorage;

    private AliasHelper $aliasHelper;

    public function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->aliasHelper = new AliasHelper();
    }

    public function testApply(): void
    {
        $filter = new FilterByUserExtension($this->tokenStorage, $this->aliasHelper);

        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getId')->willReturn(Uuid::uuid4()->toString());

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $queryExpr = new Expr();

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->exactly(2))->method('expr')->willReturn($queryExpr);

        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $filter->apply($queryBuilder, Request::createFromGlobals());
    }

    public function testSupport(): void
    {
        $filter = new FilterByUserExtension($this->tokenStorage, $this->aliasHelper);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())->method('getPathInfo')->willReturn('/admin');

        $this->assertSame(true, $filter->support($this->createMock(ApiClientInterface::class)::class, $request));
    }
}
