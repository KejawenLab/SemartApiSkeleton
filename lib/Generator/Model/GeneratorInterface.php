<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator\Model;

use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface GeneratorInterface
{
    public const SCOPE_API = 'api';

    public const SCOPE_ADMIN = 'admin';

    public const SCOPE_ALL = 'all';

    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void;

    public function support(string $scope): bool;
}
