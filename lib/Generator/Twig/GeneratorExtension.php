<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator\Twig;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GeneratorExtension extends AbstractExtension
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFilters(): iterable
    {
        yield new TwigFilter('pluralize', [$this, 'pluralize']);
        yield new TwigFilter('humanize', [$this, 'humanize']);
        yield new TwigFilter('underscore', [$this, 'underscore']);
        yield new TwigFilter('dash', [$this, 'dash']);
        yield new TwigFilter('camelcase', [$this, 'camelcase']);
    }

    public function getFunctions(): iterable
    {
        yield new TwigFunction('semart_association', [$this, 'hasAssociation']);
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

    public function hasAssociation(\ReflectionClass $class, \ReflectionProperty $property): bool
    {
        return $this->entityManager->getClassMetadata($class->getName())->hasAssociation($property);
    }
}
