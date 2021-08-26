<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Model;

use DateTimeImmutable;
use DateTimeInterface;
use DateTime;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface CronReportInterface extends EntityInterface
{
    public function getCron(): ?CronInterface;

    /**
     * @return DateTime|DateTimeImmutable
     */
    public function getRunAt(): DateTimeInterface;

    public function getRuntime(): float;

    public function getOutput(): ?string;

    public function getExitCode(): int;

    public function isSuccessful(): bool;

    public function isError(): bool;
}
