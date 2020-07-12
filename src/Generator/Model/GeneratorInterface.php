<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator\Model;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface GeneratorInterface
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void;
}
