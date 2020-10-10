<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SettingExtension extends AbstractExtension
{
    private SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function getSetting(string $key)
    {
        return $this->service->getSetting($key);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setting', [$this, 'getSetting']),
        ];
    }
}
