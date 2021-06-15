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
    public function __construct(private SettingService $service)
    {
    }

    public function getSetting(string $key): Model\SettingInterface
    {
        return $this->service->getSetting($key);
    }

    public function getFunctions(): iterable
    {
        yield new TwigFunction('setting', [$this, 'getSetting']);
    }
}
