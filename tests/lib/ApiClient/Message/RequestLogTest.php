<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\ApiClient\Message;

use KejawenLab\ApiSkeleton\ApiClient\Message\RequestLog;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class RequestLogTest extends TestCase
{
    public function testEmptyRequest(): void
    {
        $apiClient = $this->createMock(ApiClientInterface::class);
        $apiClient->expects($this->once())->method('getId')->willReturn('test');

        $request = Request::createFromGlobals();

        $requestLog = new RequestLog($apiClient, $request);

        $this->assertSame('test', $requestLog->getApiClientId());
        $this->assertSame($request->headers->all(), $requestLog->getHeaders());
        $this->assertSame($request->query->all(), $requestLog->getQueries());
        $this->assertSame($request->request->all(), $requestLog->getRequests());
        $this->assertSame($request->files->all(), $requestLog->getFiles());
    }

    public function testRequestWithValue(): void
    {
        $apiClient = $this->createMock(ApiClientInterface::class);
        $apiClient->expects($this->once())->method('getId')->willReturn('test');

        $request = Request::createFromGlobals();
        $request->query->set('test', true);

        $requestLog = new RequestLog($apiClient, $request);

        $this->assertSame('test', $requestLog->getApiClientId());
        $this->assertSame($request->headers->all(), $requestLog->getHeaders());
        $this->assertSame($request->query->all(), $requestLog->getQueries());
        $this->assertSame($request->request->all(), $requestLog->getRequests());
        $this->assertSame($request->files->all(), $requestLog->getFiles());
        $this->assertSame($request->query->get('test'), $requestLog->getQueries()['test']);
    }
}
