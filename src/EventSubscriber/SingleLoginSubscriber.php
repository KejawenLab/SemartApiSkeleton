<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\EventSubscriber;

use Alpabit\ApiSkeleton\Security\Service\UserService;
use Alpabit\ApiSkeleton\Util\Encryptor;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SingleLoginSubscriber implements EventSubscriberInterface
{
    private $requestStack;

    private $service;

    public function __construct(RequestStack $requestStack, UserService $service)
    {
        $this->requestStack = $requestStack;
        $this->service = $service;
    }

    public function validate(JWTDecodedEvent $event): void
    {
        $payload = $event->getPayload();
        if (!isset($payload['deviceId'])) {
            $event->markAsInvalid();
            $event->stopPropagation();

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
        $user = $event->getUser();
        $payload = $event->getData();

        $deviceId = Encryptor::hash(date('YmdHis'));
        $payload['deviceId'] = $deviceId;

        $user->setDeviceId($deviceId);
        $this->service->save($user);

        $event->setData($payload);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            JWTCreatedEvent::class => 'sign',
            JWTDecodedEvent::class => 'validate',
        ];
    }
}
