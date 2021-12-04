<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Controller\Me\Profile;
use KejawenLab\ApiSkeleton\Controller\User\GetAll;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class LoadUrlPathSubscriber implements EventSubscriber
{
    private const ROUTE_NAMESPACE_PREFIX = 'KejawenLab\\Application\\Controller\\';

    private array $reservedRoutes = [
        Profile::class,
        GetAll::class,
        \KejawenLab\ApiSkeleton\Controller\Group\GetAll::class,
        \KejawenLab\ApiSkeleton\Controller\Menu\GetAll::class,
        \KejawenLab\ApiSkeleton\Controller\Setting\GetAll::class,
        \KejawenLab\ApiSkeleton\Controller\ApiClient\GetAll::class,
        \KejawenLab\ApiSkeleton\Controller\Cron\GetAll::class,
        \KejawenLab\ApiSkeleton\Controller\Media\GetAll::class,
    ];

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();
        if (!$object instanceof MenuInterface) {
            return;
        }

        $apiPath = '#';
        $path = $object->getRouteName();
        $adminPath = '#';
        if ($object->isAdminOnly()) {
            $adminRoute = $object->getRouteName();
            try {
                if ('#' !== $adminRoute) {
                    $adminPath = $this->urlGenerator->generate($adminRoute);
                }
            } catch (RouteNotFoundException) {
            }
        } else {
            try {
                if ('#' !== $path) {
                    $apiPath = $this->urlGenerator->generate($object->getRouteName());
                }
            } catch (RouteNotFoundException) {
            }

            $placeHolder = self::ROUTE_NAMESPACE_PREFIX;
            if (in_array($object->getRouteName(), $this->reservedRoutes)) {
                $placeHolder = 'KejawenLab\\ApiSkeleton\\Controller\\';
            }

            $replece = StringUtil::replace($placeHolder, 'Controller\\', 'Admin\\Controller\\');
            $adminRoute = StringUtil::replace($object->getRouteName(), $placeHolder, $replece);
            try {
                $adminPath = $this->urlGenerator->generate(StringUtil::replace($adminRoute, 'GetAll', 'Main'));
            } catch (RouteNotFoundException) {
            }
        }

        $object->setApiPath($apiPath);
        $object->setAdminPath($adminPath);
    }

    /**
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }
}
