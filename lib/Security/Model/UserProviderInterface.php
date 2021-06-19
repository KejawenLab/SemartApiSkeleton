<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface UserProviderInterface
{
    public function findByIdentifier(string $identifier): ?AuthInterface;

    public function support(string $class): bool;
}
