<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator\Twig;

use KejawenLab\ApiSkeleton\Util\StringUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GeneratorExtension extends AbstractExtension
{
    private const SEMART_PREFIX = 'KejawenLab\\ApiSkeleton\\Application\\Entity';

    public function getFilters(): iterable
    {
        yield new TwigFilter('pluralize', [$this, 'pluralize']);
        yield new TwigFilter('humanize', [$this, 'humanize']);
        yield new TwigFilter('underscore', [$this, 'underscore']);
        yield new TwigFilter('dash', [$this, 'dash']);
        yield new TwigFilter('camelcase', [$this, 'camelcase']);
        yield new TwigFilter('is_semart', [$this, 'isSemart']);
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

    public function isSemart(\ReflectionProperty $property): bool
    {
        $type = $property->getType();
        if (strpos($type, static::SEMART_PREFIX)) {
            return true;
        }

        return false;
    }
}
