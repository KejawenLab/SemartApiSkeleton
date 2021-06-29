<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\EventSubscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\ApiSkeleton\EventSubscriber\LoadUrlPathSubscriber;
use PHPUnit\Framework\TestCase;
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

    public function testGetEventClass(): void
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $subscriber = new LoadUrlPathSubscriber($urlGenerator);

        $events = $subscriber->getSubscribedEvents();

        $this->assertCount(1, $events);
        $this->assertSame(Events::postLoad, $events[0]);
    }
}
