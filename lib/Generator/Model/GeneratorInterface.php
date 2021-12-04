<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator\Model;

use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface GeneratorInterface
{
    final public const SCOPE_API = 'api';

    final public const SCOPE_ADMIN = 'admin';

    final public const SCOPE_ALL = 'all';

    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void;

    public function support(string $scope): bool;
}
