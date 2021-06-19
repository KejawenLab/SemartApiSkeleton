<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRequestInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClientRequest;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ApiClientRequestTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new ApiClientRequest();

        $this->assertTrue($class instanceof ApiClientRequestInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
