<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class InvalidateDoctrineCacheSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function invalidate(KernelEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if ($request->isMethod(Request::METHOD_GET)) {
            return;
        }

        if ($request->isMethod(Request::METHOD_HEAD)) {
            return;
        }

        if ($request->isMethod(Request::METHOD_OPTIONS)) {
            return;
        }

        $configuration = $this->entityManager->getConfiguration();

        $configuration->getQueryCache()->clear();
        $configuration->getResultCache()->clear();
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [['invalidate', 27]],
            ResponseEvent::class => [['invalidate', -27]],
        ];
    }
}
