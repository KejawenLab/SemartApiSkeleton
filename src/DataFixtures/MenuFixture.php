<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\DataFixtures;

use Alpabit\ApiSkeleton\Entity\Menu;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class MenuFixture extends AbstractFixture
{
    protected function createNew()
    {
        return new Menu();
    }

    protected function getReferenceKey(): string
    {
        return 'menu';
    }
}
