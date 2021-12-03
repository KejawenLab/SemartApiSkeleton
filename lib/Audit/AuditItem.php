<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AuditItem
{
    public function __construct(
        private readonly string $type,
        /*
         * @var array<string, mixed>
         */
        private readonly array $data,
        private readonly ?string $logTime,
        private readonly ?string $userId,
        private readonly ?string $username,
        private readonly ?string $ip,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
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
