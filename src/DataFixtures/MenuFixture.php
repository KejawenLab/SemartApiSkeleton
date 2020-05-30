<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\DataFixtures;

use KejawenLab\Semart\ApiSkeleton\Entity\Menu;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class MenuFixture extends AbstractFixture
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
