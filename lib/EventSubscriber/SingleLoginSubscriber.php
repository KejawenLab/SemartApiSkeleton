<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SingleLoginSubscriber implements EventSubscriberInterface
{
    private const API_CLIENT_DEVICE_ID = 'API_CLIENT_DEVICE_ID';

    private RequestStack $requestStack;

    private UrlGeneratorInterface $urlGenerator;

    private UserService $service;

    private UserProviderFactory $userProviderFactory;

    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $urlGenerator, UserService $service, UserProviderFactory $userProviderFactory)
    {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->service = $service;
        $this->userProviderFactory = $userProviderFactory;
    }

    public function validate(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!AdminContext::isAdminContext($request)) {
            return;
        }

        $session = $request->getSession();
        if (!$deviceId = $session->get(AdminContext::USER_DEVICE_ID)) {
            return;
        }

        $user = $this->service->getByDeviceId($deviceId);
        if ($user) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('admin_logout')));
    }

    public function decode(JWTDecodedEvent $event): void
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
        if ($user) {
            return;
        }

        $event->markAsInvalid();
        $event->stopPropagation();
    }

    public function create(JWTCreatedEvent $event): void
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
            RequestEvent::class => [['validate', -255]],
            JWTCreatedEvent::class => 'create',
            JWTDecodedEvent::class => 'decode',
            Events::JWT_CREATED => 'create',
            Events::JWT_DECODED => 'decode',
        ];
    }
}
