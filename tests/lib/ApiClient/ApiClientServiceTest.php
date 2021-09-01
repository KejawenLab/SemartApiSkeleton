<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\Message\EntityPersisted;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ApiClientServiceTest extends TestCase
{
    public function testSave(): void
    {
        $client = new ApiClient();
        $message = new EntityPersisted($client);

        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects($this->once())->method('dispatch')->with($message)->willReturn(new Envelope($message));

        $repository = $this->createMock(ApiClientRepositoryInterface::class);
        $repository->expects($this->once())->method('persist')->with($client);
        $repository->expects($this->once())->method('commit');

        $helper = new AliasHelper();

        $servive = new ApiClientService($bus, $repository, $helper);

        $servive->save($client);
    }
}
