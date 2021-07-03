<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\EventSubscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\EventSubscriber\LoadUrlPathSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class LoadUrlPathSubscriberTest extends TestCase
{
    public function testInvalidObject(): void
    {
        $event = $this->createMock(LifecycleEventArgs::class);
        $event->expects($this->once())->method('getObject')->willReturn(new class {});

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $subscriber = new LoadUrlPathSubscriber($urlGenerator);

        $subscriber->postLoad($event);
    }

    public function testIsAdminOnly(): void
    {
        $menu = new Menu();
        $menu->setAdminOnly(true);
        $menu->setRouteName('#');

        $event = $this->createMock(LifecycleEventArgs::class);
        $event->expects($this->once())->method('getObject')->willReturn($menu);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->never())->method('generate');

        $subscriber = new LoadUrlPathSubscriber($urlGenerator);

        $subscriber->postLoad($event);
    }

    public function testNotFoundRoute(): void
    {
        $menu = new Menu();
        $menu->setAdminOnly(true);
        $menu->setRouteName('fake_route');

        $event = $this->createMock(LifecycleEventArgs::class);
        $event->expects($this->once())->method('getObject')->willReturn($menu);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->once())->method('generate')->willThrowException(new RouteNotFoundException());

        $subscriber = new LoadUrlPathSubscriber($urlGenerator);

        $subscriber->postLoad($event);
        $this->assertSame('#', $menu->getAdminPath());
    }

    public function testGetEventClass(): void
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $subscriber = new LoadUrlPathSubscriber($urlGenerator);

        $events = $subscriber->getSubscribedEvents();

        $this->assertCount(1, $events);
        $this->assertSame(Events::postLoad, $events[0]);
    }
}
