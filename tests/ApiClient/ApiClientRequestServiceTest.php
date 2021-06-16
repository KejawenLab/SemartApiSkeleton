<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientRequestService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestRepositoryInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class ApiClientRequestServiceTest extends TestCase
{
    public function testCreateFromRequest(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);

        $repository = $this->createMock(ApiClientRequestRepositoryInterface::class);

        $helper = new AliasHelper();
        $class = $this->createMock(ApiClientRequestInterface::class);

        $service = new ApiClientRequestService($bus, $repository, $helper, $class::class);

        $this->assertEquals($class, $service->createFromRequest(Request::createFromGlobals()));
    }
}
