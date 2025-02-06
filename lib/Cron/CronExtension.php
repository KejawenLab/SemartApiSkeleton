<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use Iterator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronExtension extends AbstractExtension
{
    /**
     * @return Iterator<TwigFunction>
     */
    #[\Override]
    public function getFunctions(): iterable
    {
        yield new TwigFunction('normalize_cron_log', [$this, 'normalize']);
    }

    /**
     * @return array<int, string>
     */
    public function normalize(string $log): array
    {
        $temps = explode(\PHP_EOL, $log);
        $vars = [];
        foreach ($temps as $temp) {
            if ('' !== $temp) {
                $vars[] = $temp;
            }
        }

        return $vars;
    }
}
