<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\DataFixtures;

use Alpabit\ApiSkeleton\Entity\Group;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GroupFixture extends AbstractFixture
{
    protected function createNew()
    {
        return new Group();
    }

    protected function getReferenceKey(): string
    {
        return 'group';
    }
}
