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
    public function __construct(private Reader $reader)
    {
    }

    public function parse(ReflectionClass $metadata): ?Permission
    {
        $class = $this->reader->getClassAnnotation($metadata, Permission::class);
        if (!$class instanceof Permission) {
            return null;
        }

        return new Permission([
            'menu' => $class->getMenu(),
            'actions' => $class->getActions(),
            'ownership' => $class->isOwnership(),
        ]);
    }
}
