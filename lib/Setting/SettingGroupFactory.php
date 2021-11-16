<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting;

use KejawenLab\ApiSkeleton\Setting\Model\SettingGroupInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SettingGroupFactory
{
    /**
     * @param SettingGroupInterface[] $groups
     */
    public function __construct(private iterable $groups)
    {
    }

    /**
     * @return array<int|string, string>
     */
    public function getGroups(): array
    {
        $groups = [
            'pagination' => 'pagination',
            'performance' => 'performance',
        ];

        foreach ($this->groups as $group) {
            $groups[$group->geKey()] = $group->getValue();
        }

        return $groups;
    }
}
