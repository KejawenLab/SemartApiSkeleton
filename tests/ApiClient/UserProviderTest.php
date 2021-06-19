<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRepositoryInterface;
use KejawenLab\ApiSkeleton\ApiClient\UserProvider;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class UserProviderTest extends TestCase
{
    private ApiClientRepositoryInterface $repository;

    private string $class;

    public function setUp(): void
    {
        $this->repository = $this->createMock(ApiClientRepositoryInterface::class);
        $this->class = 'App\\Test';
    }

    public function testSupport(): void
    {
        $provider = new UserProvider($this->class, $this->repository);
        $this->assertSame(true, $provider->support($this->class));
    }

    public function testFindByIdentifier(): void
    {
        $secret = 's3cr3t';

        $client = new ApiClient();
        $client->setApiKey($secret);

        $this->repository->expects($this->once())->method('findByApiKey')->with($secret)->willReturn($client);

        $provider = new UserProvider($this->class, $this->repository);

        $this->assertSame($client, $provider->findByIdentifier($secret));
    }
}
