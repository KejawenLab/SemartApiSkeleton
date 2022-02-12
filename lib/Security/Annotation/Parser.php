<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Annotation;

use Doctrine\Common\Annotations\Reader;
use ReflectionClass;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Parser
{
    public function __construct(private readonly Reader $reader)
    {
    }

    public function parse(ReflectionClass $metadata): ?Permission
    {
        $attributes = $metadata->getAttributes(Permission::class);
        foreach ($attributes as $attribute) {
            return $attribute->newInstance();
        }

        return $this->reader->getClassAnnotation($metadata, Permission::class);
    }
}
