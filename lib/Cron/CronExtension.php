<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronExtension extends AbstractExtension
{
    public function getFunctions(): iterable
    {
        yield new TwigFunction('normalize_cron_log', [$this, 'normalize']);
    }

    public function normalize(string $log): array
    {
        $temps = explode(PHP_EOL, $log);
        $vars = [];
        foreach ($temps as $temp) {
            if ($temp) {
                $vars[] = $temp;
            }
        }

        return $vars;
    }
}
