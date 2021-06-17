<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Query;

use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Query\CronSearchQueryExtension;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronSearchQueryExtensionTest extends TestCase
{
    public function testSupport(): void
    {
        $queryExtension = new CronSearchQueryExtension(new AliasHelper());

        $request = Request::createFromGlobals();
        $request->query->set('q', 'abc');

        $this->assertSame(false, $queryExtension->support($request::class, $request));
        $this->assertSame(false, $queryExtension->support(CronInterface::class, Request::createFromGlobals()));
        $this->assertSame(true, $queryExtension->support($this->createMock(CronInterface::class)::class, $request));
    }
}
