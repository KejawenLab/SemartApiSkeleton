<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Setting;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class SettingFixture extends AbstractFixture
{
    protected function createNew()
    {
        return new Setting();
    }

    protected function getReferenceKey(): string
    {
        return 'setting';
    }
}
