<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting;

use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use Iterator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SettingExtension extends AbstractExtension
{
    public function __construct(private SettingService $service)
    {
    }

    /**
     * @return Iterator<TwigFunction>
     */
    public function getFunctions(): iterable
    {
        yield new TwigFunction('setting', [$this, 'getSetting']);
    }

    public function getSetting(string $key): SettingInterface
    {
        return $this->service->getSetting($key);
    }
}
