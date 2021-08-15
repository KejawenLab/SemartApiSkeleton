<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronBuilder
{
    public function __construct(private KernelInterface $kernel)
    {
    }

    public function build(CronInterface $cron): ?string
    {
        if ($cron->isSymfonyCommand()) {
            $docRoot = explode('/', $_SERVER['NGINX_WEBROOT']);
            array_pop($docRoot);

            return sprintf(
                '%s %s/bin/console %s --env=%s',
                (new PhpExecutableFinder())->find(),
                implode('/', $docRoot),
                $cron->getCommand(),
                $this->kernel->getEnvironment()
            );
        }

        return $cron->getCommand();
    }
}
