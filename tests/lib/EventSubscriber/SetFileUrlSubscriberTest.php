<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\EventSubscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\EventSubscriber\SetFileUrlSubscriber;
use PHPUnit\Framework\TestCase;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class SetFileUrlSubscriberTest extends TestCase
{
    public function testInvalidObject(): void
    {
        $event = $this->createMock(LifecycleEventArgs::class);
        $event->expects($this->once())->method('getObject')->willReturn(new class {});

        $storage = $this->createMock(StorageInterface::class);
        $subscriber = new SetFileUrlSubscriber($storage);

        $subscriber->postLoad($event);
    }

    public function testValidObject(): void
    {
        $media = new Media();

        $event = $this->createMock(LifecycleEventArgs::class);
        $event->expects($this->once())->method('getObject')->willReturn($media);

        $storage = $this->createMock(StorageInterface::class);
        $storage->expects($this->once())->method('resolveUri')->willReturn('/fake/url');

        $subscriber = new SetFileUrlSubscriber($storage);

        $subscriber->postLoad($event);

        $this->assertSame($media->getFileUrl(), '/fake/url');
    }

    public function testGetEventClass(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $subscriber = new SetFileUrlSubscriber($storage);

        $events = $subscriber->getSubscribedEvents();

        $this->assertCount(1, $events);
        $this->assertSame(Events::postLoad, $events[0]);
    }
}
