<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface CronInterface
{
    public const PID_FILE = '.semart.pid';

    public const CRON_RUN_KEY = 'CRON_RUN';

    public function getName(): ?string;

    public function getDescription(): ?string;

    public function getCommand(): ?string;

    public function getSchedule(): ?string;

    public function isEnabled(): bool;

    public function isSymfonyCommand(): bool;

    public function isRunning(): bool;
}
