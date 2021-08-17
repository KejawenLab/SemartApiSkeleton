<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Audit
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $items = [];

    public function __construct(private object $entity)
    {
    }

    public function addItem(AuditItem $item): void
    {
        $this->items[] = $item->toArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'entity' => $this->entity,
            'items' => $this->items,
        ];
    }
}
