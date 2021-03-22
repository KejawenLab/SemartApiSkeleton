<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SingleLoginSubscriber implements EventSubscriberInterface
{
    private const API_CLIENT_DEVICE_ID = 'API_CLIENT_DEVICE_ID';

    private RequestStack $requestStack;

    private UserService $service;

    private UserProviderFactory $userProviderFactory;

    public function __construct(RequestStack $requestStack, UserService $service, UserProviderFactory $userProviderFactory)
    {
        $this->requestStack = $requestStack;
        $this->service = $service;
        $this->userProviderFactory = $userProviderFactory;
    }

    public function validate(JWTDecodedEvent $event): void
    {
        $payload = $event->getPayload();
        if (!isset($payload['deviceId'])) {
            $event->markAsInvalid();
            $event->stopPropagation();

            return;
        }

        if (static::API_CLIENT_DEVICE_ID === $payload['deviceId']) {
            return;
        }

        $user = $this->service->getByDeviceId($payload['deviceId']);
        if (!$user) {
            $event->markAsInvalid();
            $event->stopPropagation();

            return;
        }
    }

    public function sign(JWTCreatedEvent $event): void
    {
        $user = $this->userProviderFactory->getRealUser($event->getUser());
        $payload = $event->getData();

        $deviceId = Encryptor::hash(date('YmdHis'));
        $payload['deviceId'] = $deviceId;

        if ($user instanceof UserInterface) {
            $user->setDeviceId($deviceId);
            $this->service->save($user);
        } else {
            $payload['deviceId'] = static::API_CLIENT_DEVICE_ID;
        }

        $event->setData($payload);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            JWTCreatedEvent::class => 'sign',
            JWTDecodedEvent::class => 'validate',
            Events::JWT_CREATED => 'sign',
            Events::JWT_DECODED => 'validate',
        ];
    }
}
