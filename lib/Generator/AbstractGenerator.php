<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Generator\Model\GeneratorInterface;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    public function __construct(
        protected Environment $twig,
        protected Filesystem $fileSystem,
        protected KernelInterface $kernel,
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function hasAssociation(ReflectionClass $class): bool
    {
        $metadata = $this->entityManager->getClassMetadata($class->getName());
        foreach ($class->getProperties(ReflectionProperty::IS_PRIVATE) as $property) {
            if ($metadata->hasAssociation($property->getName())) {
                return true;
            }
        }

        return false;
    }
}
