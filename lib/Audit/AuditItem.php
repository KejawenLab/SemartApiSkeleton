<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

use DateTimeImmutable;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AuditItem
{
    public function __construct(
        private readonly string             $type,
        private readonly array              $data,
        private readonly ?DateTimeImmutable $logTime,
        private readonly ?string            $userId,
        private readonly ?string            $username,
        private readonly ?string            $ip,
    )
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'log_time' => $this->logTime?->format('Y-m-d H:i:s'),
            'user_id' => $this->userId,
            'username' => $this->username,
            'ip_address' => $this->ip,
            'data' => $this->data,
        ];
    }
}
