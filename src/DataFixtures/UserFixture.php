<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\DataFixtures;

use Alpabit\ApiSkeleton\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class UserFixture extends AbstractFixture implements DependentFixtureInterface
{
    protected function createNew()
    {
        return new User();
    }

    protected function getReferenceKey(): string
    {
        return 'user';
    }

    public function getDependencies()
    {
        return [
            GroupFixture::class,
        ];
    }
}
