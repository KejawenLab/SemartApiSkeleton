<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\ApiClient\Query;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Query\FilterByUserExtension;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    public function testSupport(): void
    {
        $filter = new FilterByUserExtension($this->tokenStorage, $this->aliasHelper);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())->method('getPathInfo')->willReturn('/admin');

        $this->assertSame(true, $filter->support($this->createMock(ApiClientInterface::class)::class, $request));
    }
}
