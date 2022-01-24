<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Security\User;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SingleLoginSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserService $service,
        private readonly UserProviderFactory $userProviderFactory,
        private readonly CacheItemPoolInterface $cache,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function validate(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!AdminContext::isAdminContext($request)) {
            return;
        }

        $deviceId = $request->getSession()->get(AdminContext::USER_DEVICE_ID);
        if (null === $deviceId) {
            return;
        }

        $user = $this->service->getByDeviceId($deviceId);
        if (null !== $user) {
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

            $this->cache->deleteItem(SettingInterface::CACHE_ID_CACHE_LIFETIME);
            $this->cache->deleteItem(SettingInterface::CACHE_ID_PAGE_FIELD);
            $this->cache->deleteItem(SettingInterface::CACHE_ID_PER_PAGE_FIELD);
            $this->cache->deleteItem(SettingInterface::CACHE_ID_PER_PAGE);
            $this->cache->deleteItem(SettingInterface::CACHE_ID_MAX_API_PER_USER);

            $configuration = $this->entityManager->getConfiguration();

            $configuration->getQueryCache()->clear();
            $configuration->getResultCache()->clear();

            return;
        }

        if (ApiClientInterface::DEVICE_ID === $payload['deviceId']) {
            return;
        }

        $user = $this->service->getByDeviceId($payload['deviceId']);
        if (null !== $user) {
            return;
        }

        $event->markAsInvalid();
        $event->stopPropagation();

        $this->cache->deleteItem(SettingInterface::CACHE_ID_CACHE_LIFETIME);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PAGE_FIELD);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PER_PAGE_FIELD);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PER_PAGE);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_MAX_API_PER_USER);

        $configuration = $this->entityManager->getConfiguration();

        $configuration->getQueryCache()->clear();
        $configuration->getResultCache()->clear();
    }

    public function create(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
        if (!$user instanceof User) {
            return;
        }

        $user = $this->userProviderFactory->getRealUser($user);
        $payload = $event->getData();

        $deviceId = Encryptor::hash(date('YmdHis'));
        $payload['deviceId'] = $deviceId;

        if ($user instanceof UserInterface) {
            $user->setLastLogin(new DateTimeImmutable());
            $user->setDeviceId($deviceId);
            $this->service->save($user);
        } else {
            $payload['deviceId'] = ApiClientInterface::DEVICE_ID;
        }

        $event->setData($payload);

        $this->cache->deleteItem(SettingInterface::CACHE_ID_CACHE_LIFETIME);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PAGE_FIELD);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PER_PAGE_FIELD);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PER_PAGE);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_MAX_API_PER_USER);

        $configuration = $this->entityManager->getConfiguration();

        $configuration->getQueryCache()->clear();
        $configuration->getResultCache()->clear();
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [['validate', -255]],
            JWTCreatedEvent::class => 'create',
            JWTDecodedEvent::class => 'decode',
        ];
    }
}
