<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface CronInterface extends EntityInterface
{
    final public const PID_FILE = '.semart.pid';

    final public const CRON_RUN_KEY = 'CRON_RUN';

    public function getName(): ?string;

    public function getDescription(): ?string;

    public function getCommand(): ?string;

    public function getSchedule(): ?string;

    public function isEnabled(): bool;

    public function isSymfonyCommand(): bool;

    public function isRunning(): bool;
}
