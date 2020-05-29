<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator;

use KejawenLab\Semart\ApiSkeleton\Generator\Model\GeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    protected $twig;

    protected $fileSystem;

    protected $kernel;

    public function __construct(Environment $twig, Filesystem $fileSystem, KernelInterface $kernel)
    {
        $this->twig = $twig;
        $this->fileSystem = $fileSystem;
        $this->kernel = $kernel;
    }
}
