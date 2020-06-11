<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Audit;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class AuditItem
{
    private string $type;

    private array $data;

    private ?string $userId;

    private ?string $username;

    private ?string $ip;

    public function __construct(string $type, array $data, ?string $userId, ?string $username, ?string $ip)
    {
        $this->type = $type;
        $this->data = $data;
        $this->userId = $userId;
        $this->username = $username;
        $this->ip = $ip;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'user_id' => $this->userId,
            'username' => $this->username,
            'ip_address' => $this->ip,
            'data' => $this->data,
        ];
    }
}
