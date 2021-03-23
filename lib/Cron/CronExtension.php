<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use Symfony\Component\VarDumper\VarDumper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('dump_cron_log', [$this, 'dump']),
        ];
    }

    public function dump($variable)
    {
        $temps = explode(PHP_EOL, $variable);
        $vars = [];
        $vars['logs'] = [];
        foreach ($temps as $temp) {
            if ($temp) {
                $vars['logs'][] = $temp;
            }
        }

        return VarDumper::dump($vars);
    }
}
