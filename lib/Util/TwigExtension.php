<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Util;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class TwigExtension extends AbstractExtension
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        } else {
            return $data ?: '';
        }
    }

    public function hasAssociation(\ReflectionClass $class, \ReflectionProperty $property): bool
    {
        return $this->entityManager->getClassMetadata($class->getName())->hasAssociation($property->getName());
    }
}
