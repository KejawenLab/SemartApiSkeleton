<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AliasHelper
{
    private array $aliases = [];

    public function findAlias(string $field): ?string
    {
        if (!array_key_exists($field, $this->aliases)) {
            $this->aliases[$field] = $this->getAlias(array_values($this->aliases));
        }

        return $this->aliases[$field];
    }

    private function getAlias(array $exclude = []): string
    {
        $list = 'abcdefghijklmnopqrstuvwxyz';
        $alias = $list[random_int(0, \strlen($list) - 1)];
        if (\in_array($alias, $exclude)) {
            return $this->getAlias($exclude);
        }

        return $alias;
    }
}
