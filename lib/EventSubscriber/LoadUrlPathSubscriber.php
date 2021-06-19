<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

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
    private const ROUTE_NAMESPACE_PREFIX = 'KejawenLab\\ApiSkeleton\\Application\\Controller\\';

    private array $reservedRoutes = [
        'KejawenLab\\ApiSkeleton\\Controller\\Me\\Profile',
        'KejawenLab\\ApiSkeleton\\Controller\\User\\GetAll',
        'KejawenLab\\ApiSkeleton\\Controller\\Group\\GetAll',
        'KejawenLab\\ApiSkeleton\\Controller\\Menu\\GetAll',
        'KejawenLab\\ApiSkeleton\\Controller\\Setting\\GetAll',
        'KejawenLab\\ApiSkeleton\\Controller\\ApiClient\\GetAll',
        'KejawenLab\\ApiSkeleton\\Controller\\Cron\\GetAll',
        'KejawenLab\\ApiSkeleton\\Controller\\Media\\GetAll',
    ];

    public function __construct(private UrlGeneratorInterface $urlGenerator)
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
            } catch (RouteNotFoundException $e) {
            }
        } else {
            try {
                if ('#' !== $path) {
                    $apiPath = $this->urlGenerator->generate($object->getRouteName());
                }
            } catch (RouteNotFoundException $e) {
            }

            $placeHolder = self::ROUTE_NAMESPACE_PREFIX;
            if (in_array($object->getRouteName(), $this->reservedRoutes)) {
                $placeHolder = StringUtil::replace($placeHolder, 'Application\\', '');
            }

            $replece = StringUtil::replace($placeHolder, 'Controller\\', 'Admin\\Controller\\');
            $adminRoute = StringUtil::replace($object->getRouteName(), $placeHolder, $replece);
            try {
                $adminPath = $this->urlGenerator->generate($adminRoute);
            } catch (RouteNotFoundException $e) {
            }
        }

        $object->setApiPath($apiPath);
        $object->setAdminPath($adminPath);
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }
}
