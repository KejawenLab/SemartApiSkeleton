<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientRequestService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ApiClientRequestSubscriber implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;

    private ApiClientRequestService $apiClientRequestService;

    private UserProviderFactory $userProvider;

    public function __construct(TokenStorageInterface $tokenStorage, ApiClientRequestService $apiClientRequestService, UserProviderFactory $userProvider)
    {
        $this->tokenStorage = $tokenStorage;
        $this->apiClientRequestService = $apiClientRequestService;
        $this->userProvider = $userProvider;
    }

    public function log(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return;
        }

        $user = $this->userProvider->getRealUser($token->getUser());
        if (!$user instanceof ApiClientInterface) {
            return;
        }

        $apiClientRequest = $this->apiClientRequestService->createFromRequest($event->getRequest());
        $apiClientRequest->setApiClient($user);

        $this->apiClientRequestService->save($apiClientRequest);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'log',
        ];
    }
}
