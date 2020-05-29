<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator\Twig;

use KejawenLab\Semart\ApiSkeleton\Util\StringUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GeneratorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('pluralize', [$this, 'pluralize']),
            new TwigFilter('humanize', [$this, 'humanize']),
            new TwigFilter('underscore', [$this, 'underscore']),
            new TwigFilter('dash', [$this, 'dash']),
            new TwigFilter('camelcase', [$this, 'camelcase']),
        ];
    }

    public function pluralize(string $value): string
    {
        return StringUtil::plural($value);
    }

    public function humanize(string $value): string
    {
        return StringUtil::title($value, false);
    }

    public function underscore(string $value): string
    {
        return StringUtil::underscore($value);
    }

    public function dash(string $value): string
    {
        return StringUtil::dash($value);
    }

    public function camelcase(string $value): string
    {
        return StringUtil::camelcase($value);
    }
}
