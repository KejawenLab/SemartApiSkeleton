<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Cron\Model\CronInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronBuilder
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function build(CronInterface $cron): string
    {
        if ($cron->isSymfonyCommand()) {
            return sprintf('%s %s %s --env=%s', (new PhpExecutableFinder())->find(), $_SERVER['SCRIPT_NAME'], $cron->getCommand(), $this->kernel->getEnvironment());
        }

        return $cron->getCommand();
    }
}
