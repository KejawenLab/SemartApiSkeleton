<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final readonly class LogoutSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface  $urlGenerator,
        private CacheItemPoolInterface $cache,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'redirect',
        ];
    }

    public function redirect(LogoutEvent $event): void
    {
        if (!AdminContext::isAdminContext($event->getRequest())) {
            return;
        }

        $this->cache->deleteItem(SettingInterface::CACHE_ID_CACHE_LIFETIME);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PAGE_FIELD);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PER_PAGE_FIELD);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_PER_PAGE);
        $this->cache->deleteItem(SettingInterface::CACHE_ID_MAX_API_PER_USER);

        $configuration = $this->entityManager->getConfiguration();

        $configuration->getQueryCache()->clear();
        $configuration->getResultCache()->clear();

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate(AdminContext::ADMIN_ROUTE)));
    }
}
