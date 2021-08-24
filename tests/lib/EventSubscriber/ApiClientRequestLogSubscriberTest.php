<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\EventSubscriber;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientRequestService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Entity\ApiClientRequest;
use KejawenLab\ApiSkeleton\EventSubscriber\ApiClientRequestLogSubscriber;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ApiClientRequestLogSubscriberTest extends TestCase
{
    public function testOnlyMainRequestCanPass(): void
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->never())->method('getToken');

        $service = $this->createMock(ApiClientRequestService::class);
        $userProvider = $this->createMock(UserProviderFactory::class);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(false);

        $apiClientRequest = new ApiClientRequestLogSubscriber($tokenStorage, $service, $userProvider);
        $apiClientRequest->log($event);
    }

    public function testNullToken(): void
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn(null);

        $service = $this->createMock(ApiClientRequestService::class);
        $userProvider = $this->createMock(UserProviderFactory::class);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(true);

        $apiClientRequest = new ApiClientRequestLogSubscriber($tokenStorage, $service, $userProvider);
        $apiClientRequest->log($event);
    }

    public function testTokenReturnNonValidUser(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn('admin');

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $service = $this->createMock(ApiClientRequestService::class);

        $userProvider = $this->createMock(UserProviderFactory::class);
        $userProvider->expects($this->never())->method('getRealUser');

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(true);

        $apiClientRequest = new ApiClientRequestLogSubscriber($tokenStorage, $service, $userProvider);
        $apiClientRequest->log($event);
    }

    public function testTokenReturnValidUser(): void
    {
        $user = new User();
        $realUser = new ApiClient();
        $apiClientRequest = new ApiClientRequest();

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $service = $this->createMock(ApiClientRequestService::class);
        $service->expects($this->once())->method('createFromRequest')->willReturn($apiClientRequest);
        $service->expects($this->once())->method('save');

        $userProvider = $this->createMock(UserProviderFactory::class);
        $userProvider->expects($this->once())->method('getRealUser')->willReturn($realUser);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(true);
        $event->expects($this->once())->method('getRequest')->willReturn(Request::createFromGlobals());

        $apiClientRequest = new ApiClientRequestLogSubscriber($tokenStorage, $service, $userProvider);
        $apiClientRequest->log($event);
    }

    public function testGetEventClass(): void
    {
        $events = ApiClientRequestLogSubscriber::getSubscribedEvents();

        $this->assertCount(1, $events);
        $this->assertArrayHasKey(ControllerEvent::class, $events);
    }
}
