<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Audit
{
    private array $items;

    public function __construct(private object $entity)
    {
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
