<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\DataFixtures;

use KejawenLab\ApiSkeleton\Entity\Group;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GroupFixture extends AbstractFixture
{
    protected function createNew(): Group
    {
        return new Group();
    }

    protected function getReferenceKey(): string
    {
        return 'group';
    }
}
