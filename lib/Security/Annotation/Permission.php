<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Annotation;

/**
 * @Annotation()
 * @Target({"CLASS"})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Permission
{
    public final const ADD = 'add';

    public final const EDIT = 'edit';

    public final const VIEW = 'view';

    public final const DELETE = 'delete';

    private readonly string $menu;

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

        if (!isset($configs['ownership'])) {
            return;
        }

        if (!\is_bool($configs['ownership'])) {
            return;
        }

        $this->ownership = $configs['ownership'];
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
