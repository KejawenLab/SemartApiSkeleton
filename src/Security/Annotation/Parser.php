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

    public function parse(\ReflectionClass $metadata, string $action): Permission
    {
        /** @var Permission|null $class */
        $class = $this->reader->getClassAnnotation($metadata, Permission::class);
        /** @var Permission|null $method */
        $method = $this->reader->getMethodAnnotation($metadata->getMethod($action), Permission::class);

        return new Permission([
            'menu' => $class->getMenu(),
            'actions' => $method->getActions(),
            'ownership' => $class->isOwnership()?: $method->isOwnership(),
        ]);
    }
}
