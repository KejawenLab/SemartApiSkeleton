<?php

declare(strict_types=1);

namespace App\Security\Annotation;

use Doctrine\Common\Annotations\Reader;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Parser
{
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function parse(\ReflectionClass $metadata): ?Permission
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
