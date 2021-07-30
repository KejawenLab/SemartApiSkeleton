<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\EventSubscriber;

use KejawenLab\ApiSkeleton\EventSubscriber\PermissionSubscriber;
use KejawenLab\ApiSkeleton\Security\Annotation\Parser;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Authorization\Ownership;
use KejawenLab\ApiSkeleton\Security\Service\Authorization;
use KejawenLab\Stub\StubController;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class PermissionSubscriberTest extends TestCase
{
    public function testSubRequest(): void
    {
        $parser = $this->createMock(Parser::class);
        $authorization = $this->createMock(Authorization::class);
        $ownership = $this->createMock(Ownership::class);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(false);
        $event->expects($this->never())->method('getController');

        $apiClientRequest = new PermissionSubscriber($parser, $authorization, $ownership);
        $apiClientRequest->validate($event);
    }

    public function testController(): void
    {
        $parser = $this->createMock(Parser::class);
        $parser->expects($this->once())->method('parse')->willReturn(new Permission());

        $authorization = $this->createMock(Authorization::class);
        $authorization->expects($this->once())->method('authorize')->willReturn(false);

        $ownership = $this->createMock(Ownership::class);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(true);
        $event->expects($this->once())->method('getController')->willReturn(new StubController());

        $apiClientRequest = new PermissionSubscriber($parser, $authorization, $ownership);

        $this->expectException(AccessDeniedException::class);

        $apiClientRequest->validate($event);
    }

    public function testRequestAndOwnership(): void
    {
        $permission = new Permission([
            'ownership' => true,
        ]);

        $parser = $this->createMock(Parser::class);
        $parser->expects($this->once())->method('parse')->willReturn($permission);

        $authorization = $this->createMock(Authorization::class);
        $authorization->expects($this->once())->method('authorize')->willReturn(true);

        $ownership = $this->createMock(Ownership::class);
        $ownership->expects($this->once())->method('isOwner')->willReturn(false);

        $request = Request::createFromGlobals();
        $request->attributes->set('id', Uuid::uuid4()->toString());

        $event = $this->createMock(ControllerEvent::class);
        $event->expects($this->once())->method('isMainRequest')->willReturn(true);
        $event->expects($this->once())->method('getController')->willReturn(new StubController());
        $event->expects($this->once())->method('getRequest')->willReturn($request);

        $apiClientRequest = new PermissionSubscriber($parser, $authorization, $ownership);

        $this->expectException(AccessDeniedException::class);

        $apiClientRequest->validate($event);
    }

    public function testGetEventClass(): void
    {
        $events = PermissionSubscriber::getSubscribedEvents();

        $this->assertCount(1, $events);
        $this->assertArrayHasKey(ControllerEvent::class, $events);

    }
}
