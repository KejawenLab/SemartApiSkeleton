<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use KejawenLab\ApiSkeleton\Entity\Permission;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PermissionFixture extends AbstractFixture implements DependentFixtureInterface
{
    protected function createNew(): Permission
    {
        return new Permission();
    }

    protected function getReferenceKey(): string
    {
        return 'permission';
    }

    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            GroupFixture::class,
            MenuFixture::class,
        ];
    }
}
