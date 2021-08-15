<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class AuditExtension extends AbstractExtension
{
    public function __construct(private Reader $reader)
    {
    }

    public function getFunctions(): iterable
    {
        yield new TwigFunction('is_auditable', [$this, 'isAuditable']);
    }

    public function isAuditable(object $entity): bool
    {
        $class = new \ReflectionObject($entity);

        return $this->reader->getProvider()->isAuditable($class->getName());
    }
}
