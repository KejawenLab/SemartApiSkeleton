<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Annotation;

/**
 * @Annotation()
 * @Target({"CLASS"})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Permission
{
    public const ADD = 'add';

    public const EDIT = 'edit';

    public const VIEW = 'view';

    public const DELETE = 'delete';

    private string $menu;

    private array $actions = [];

    private bool $ownership = false;

    public function __construct(array $configs = [])
    {
        if (isset($configs['menu']) && \is_string($configs['menu'])) {
            $this->menu = $configs['menu'];
        }

        if (isset($configs['actions'])) {
            if (\is_string($configs['actions'])) {
                $this->actions = (array) $configs['actions'];
            }

            if (\is_array($configs['actions'])) {
                $this->actions = $configs['actions'];
            }
        }

        if (isset($configs['ownership']) && \is_bool($configs['ownership'])) {
            $this->ownership = $configs['ownership'];
        }
    }

    public function getMenu(): string
    {
        return $this->menu;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function isOwnership(): bool
    {
        return $this->ownership;
    }
}
