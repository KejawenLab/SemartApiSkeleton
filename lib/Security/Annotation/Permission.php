<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Annotation;

use Attribute;

/**
 * @Annotation()
 * @Target({"CLASS"})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Permission
{
    final public const ADD = 'add';

    final public const EDIT = 'edit';

    final public const VIEW = 'view';

    final public const DELETE = 'delete';

    /**
     * @param mixed[] $actions
     */
    public function __construct(private readonly string $menu, private readonly array $actions, private readonly bool $ownership = false)
    {
    }

    public function getMenu(): string
    {
        return $this->menu;
    }

    /**
     * @return mixed[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function isOwnership(): bool
    {
        return $this->ownership;
    }
}
