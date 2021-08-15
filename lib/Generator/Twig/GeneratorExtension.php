<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator\Twig;

use Iterator;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GeneratorExtension extends AbstractExtension
{
    /**
     * @return Iterator<TwigFilter>
     */
    public function getFilters(): iterable
    {
        yield new TwigFilter('pluralize', [$this, 'pluralize']);
        yield new TwigFilter('humanize', [$this, 'humanize']);
        yield new TwigFilter('underscore', [$this, 'underscore']);
        yield new TwigFilter('dash', [$this, 'dash']);
        yield new TwigFilter('camelcase', [$this, 'camelcase']);
    }

    public function pluralize(string $value): string
    {
        return StringUtil::plural($value);
    }

    public function humanize(string $value): string
    {
        return ucfirst($value);
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
