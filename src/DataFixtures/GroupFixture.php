<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\DataFixtures;

use KejawenLab\Semart\ApiSkeleton\Entity\Group;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class GroupFixture extends AbstractFixture
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
