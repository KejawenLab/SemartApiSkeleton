<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AuditItem
{
    public function __construct(
        private string $type,
        private array $data,
        private ?string $logTime,
        private ?string $userId,
        private ?string $username,
        private ?string $ip
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'log_time' => $this->logTime,
            'user_id' => $this->userId,
            'username' => $this->username,
            'ip_address' => $this->ip,
            'data' => $this->data,
        ];
    }
}
