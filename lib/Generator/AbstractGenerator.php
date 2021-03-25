<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Generator\Model\GeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    protected Environment $twig;

    protected Filesystem $fileSystem;

    protected KernelInterface $kernel;

    protected EntityManagerInterface $entityManager;

    public function __construct(Environment $twig, Filesystem $fileSystem, KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->fileSystem = $fileSystem;
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;
    }

    public function hasAssociation(\ReflectionClass $class): bool
    {
        $metadata = $this->entityManager->getClassMetadata($class->getName());
        $association = false;
        foreach ($class->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            if ($metadata->hasAssociation($property)) {
                ++$association;
            }
        }

        if ($association) {
            return true;
        }

        return false;
    }
}
