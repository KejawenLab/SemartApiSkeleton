<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class LoadUrlPathSubscriber implements EventSubscriber
{
    private const ROUTE_NAMESPACE_PREFIX = 'kejawenlab_apiskeleton_application_';

    private array $reservedRoutes = [
        'kejawenlab_apiskeleton_me_profile',
        'kejawenlab_apiskeleton_user_getall',
        'kejawenlab_apiskeleton_group_getall',
        'kejawenlab_apiskeleton_menu_getall',
        'kejawenlab_apiskeleton_setting_getall',
        'kejawenlab_apiskeleton_apiclient_getall',
        'kejawenlab_apiskeleton_cron_getall',
        'kejawenlab_apiskeleton_media_getall',
    ];

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
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
            } catch (RouteNotFoundException $exception) {
            }
        } else {
            try {
                if ('#' !== $path) {
                    $apiPath = $this->urlGenerator->generate($object->getRouteName());
                }
            } catch (RouteNotFoundException $exception) {
                $apiPath = $this->urlGenerator->generate(sprintf('%s__invoke', $object->getRouteName()));
            }

            $placeHolder = self::ROUTE_NAMESPACE_PREFIX;
            if (in_array($object->getRouteName(), $this->reservedRoutes)) {
                $placeHolder = StringUtil::replace($placeHolder, 'application_', '');
            }

            $replece = sprintf('%sadmin_', $placeHolder);
            $adminRoute = StringUtil::replace($object->getRouteName(), $placeHolder, $replece);
            try {
                $adminPath = $this->urlGenerator->generate(sprintf('%s__invoke', $adminRoute));
            } catch (RouteNotFoundException $exception) {
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
