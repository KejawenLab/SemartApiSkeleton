<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\EventSubscriber;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\EventSubscriber\ApiClientRequestLogSubscriber;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Messenger\MessageBusInterface;
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

        $messageBus = $this->createMock(MessageBusInterface::class);
        $userProvider = $this->createMock(UserProviderFactory::class);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(false);

        $apiClientRequest = new ApiClientRequestLogSubscriber($tokenStorage, $userProvider, $messageBus);
        $apiClientRequest->log($event);
    }

    public function testNullToken(): void
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn(null);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $userProvider = $this->createMock(UserProviderFactory::class);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(true);

        $apiClientRequest = new ApiClientRequestLogSubscriber($tokenStorage, $userProvider, $messageBus);
        $apiClientRequest->log($event);
    }

    public function testTokenReturnNonValidUser(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn('admin');

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $messageBus = $this->createMock(MessageBusInterface::class);

        $userProvider = $this->createMock(UserProviderFactory::class);
        $userProvider->expects($this->never())->method('getRealUser');

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(true);

        $apiClientRequest = new ApiClientRequestLogSubscriber($tokenStorage, $userProvider, $messageBus);
        $apiClientRequest->log($event);
    }

    public function testTokenReturnValidUser(): void
    {
        $user = new User();

        $apiClient = $this->createMock(ApiClientInterface::class);
        $apiClient->expects($this->once())->method('getId')->willReturn('test');

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $messageBus = $this->createMock(MessageBusInterface::class);

        $userProvider = $this->createMock(UserProviderFactory::class);
        $userProvider->expects($this->once())->method('getRealUser')->willReturn($apiClient);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(true);
        $event->expects($this->once())->method('getRequest')->willReturn(Request::createFromGlobals());

        $apiClientRequest = new ApiClientRequestLogSubscriber($tokenStorage, $userProvider, $messageBus);
        $apiClientRequest->log($event);
    }

    public function testGetEventClass(): void
    {
        $events = ApiClientRequestLogSubscriber::getSubscribedEvents();

        $this->assertCount(1, $events);
        $this->assertArrayHasKey(ControllerEvent::class, $events);
    }
}
