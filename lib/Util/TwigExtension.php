<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Util;

use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use ReflectionClass;
use ReflectionProperty;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class TwigExtension extends AbstractExtension
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getFunctions(): iterable
    {
        yield new TwigFunction('semart_print', [$this, 'toString']);
        yield new TwigFunction('semart_association', [$this, 'hasAssociation']);
    }

    public function toString($data): string
    {
        if ($data instanceof EntityInterface) {
            return (string) $data->getNullOrString();
        }

        if ($data instanceof DateTimeInterface) {
            return $data->format('Y-m-d');
        }

        return $data ?: '';
    }

    public function hasAssociation(ReflectionClass $class, ReflectionProperty $property): bool
    {
        return $this->entityManager->getClassMetadata($class->getName())->hasAssociation($property->getName());
    }
}
