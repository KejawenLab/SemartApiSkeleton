<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Entity;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ApiClientTest extends TestCase
{
    public function testImplement(): void
    {
        $class = new ApiClient();

        $this->assertTrue($class instanceof ApiClientInterface);
        $this->assertTrue($class instanceof EntityInterface);
    }
}
