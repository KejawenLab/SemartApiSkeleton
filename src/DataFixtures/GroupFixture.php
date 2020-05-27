<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Group;

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
