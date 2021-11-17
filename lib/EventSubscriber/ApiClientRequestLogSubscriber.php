<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\ApiClient\Message\RequestLog;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class ApiClientRequestLogSubscriber implements EventSubscriberInterface
{
    private ApiClientInterface $user;

    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private UserProviderFactory $userProvider,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function requestLog(ResponseEvent $event): void
    {
        if (!$this->isValid($event)) {
            return;
        }

        $this->messageBus->dispatch(new RequestLog($this->user, $event->getRequest(), $event->getResponse()));
    }

    public function exceptionLog(ExceptionEvent $event): void
    {
        if (!$this->isValid($event)) {
            return;
        }

        $this->messageBus->dispatch(new RequestLog($this->user, $event->getRequest(), $event->getResponse()));
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => 'requestLog',
            ExceptionEvent::class => 'exceptionLog',
        ];
    }

    private function isValid(KernelEvent $event): bool
    {
        if (!$event->isMainRequest()) {
            return false;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return false;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        $user = $this->userProvider->getRealUser($user);
        if (!$user instanceof ApiClientInterface) {
            return false;
        }

        $this->user = $user;

        return true;
    }
}
