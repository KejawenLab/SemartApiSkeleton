<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Audit;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Audit
{
    private object $entity;

    private array $items;

    public function __construct(object $entity)
    {
        $this->entity = $entity;
    }

    public function addItem(AuditItem $item): void
    {
        $this->items[] = $item->toArray();
    }

    public function toArray(): array
    {
        return [
            'entity' => $this->entity,
            'items' => $this->items,
        ];
    }
}
