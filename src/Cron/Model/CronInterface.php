<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface CronInterface
{
    public const PID_FILE = '.semart.pid';

    public const CRON_RUN_KEY = 'CRON_RUN';

    public function getName(): ?string;

    public function getDescription(): ?string;

    public function getCommand(): ?string;

    public function getSchedule(): ?string;

    public function getEstimation(): int;

    public function isEnabled(): bool;

    public function isSymfonyCommand(): bool;

    public function isRunning(): bool;
}
