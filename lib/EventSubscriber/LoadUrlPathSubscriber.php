<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\ApiSkeleton\Controller\ApiClient\GetAll as ApiClient;
use KejawenLab\ApiSkeleton\Controller\Cron\GetAll as Cron;
use KejawenLab\ApiSkeleton\Controller\Group\GetAll as Group;
use KejawenLab\ApiSkeleton\Controller\Me\Profile;
use KejawenLab\ApiSkeleton\Controller\Media\GetAll as Media;
use KejawenLab\ApiSkeleton\Controller\Menu\GetAll as Menu;
use KejawenLab\ApiSkeleton\Controller\Setting\GetAll as Setting;
use KejawenLab\ApiSkeleton\Controller\User\GetAll;
use KejawenLab\ApiSkeleton\Entity\Menu as Entity;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[AsEntityListener(event: Events::postLoad, method: 'postLoad', entity: Entity::class)]
final class LoadUrlPathSubscriber
{
    private const ROUTE_NAMESPACE_PREFIX = 'KejawenLab\\Application\\Controller\\';

    private array $reservedRoutes = [
        Profile::class, GetAll::class, Group::class, Menu::class,
        Setting::class, ApiClient::class, Cron::class, Media::class,
    ];

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function postLoad(Entity $object, PostLoadEventArgs $args): void
    {
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
            if (\in_array($object->getRouteName(), $this->reservedRoutes)) {
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
}
