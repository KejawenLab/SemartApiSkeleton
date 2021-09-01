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
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ApiClientRequestServiceTest extends TestCase
{
    public function testCreateFromRequest(): void
    {
    }
}
