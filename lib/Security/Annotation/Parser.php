<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Annotation;

use ReflectionClass;
use Doctrine\Common\Annotations\Reader;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Parser
{
    public function __construct(private Reader $reader)
    {
    }

    public function parse(ReflectionClass $metadata): ?Permission
    {
        /** @var Permission|null $class */
        $class = $this->reader->getClassAnnotation($metadata, Permission::class);
        if (!$class) {
            return null;
        }

        return new Permission([
            'menu' => $class->getMenu(),
            'actions' => $class->getActions(),
            'ownership' => $class->isOwnership(),
        ]);
    }
}
