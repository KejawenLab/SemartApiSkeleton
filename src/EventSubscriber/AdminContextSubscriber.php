<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AdminContextSubscriber implements EventSubscriberInterface
{
    private Environment $twig;

    private array $globals;

    public function __construct(Environment $twig, array $globals = [])
    {
        $this->twig = $twig;
        $this->globals = $globals;
    }

    public function apply(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!AdminContext::isAdminContext($event->getRequest())) {
            return;
        }

        $this->twig->addGlobal('sas', array_merge($this->globals, [
            'semart_name' => 'Semart Api Skeleton',
            'semart_codename' => SemartApiSkeleton::CODENAME,
            'semart_version' => SemartApiSkeleton::VERSION,
        ]));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [['apply', 9]],
        ];
    }
}
