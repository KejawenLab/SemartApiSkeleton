<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Audit;

use Symfony\Component\VarDumper\VarDumper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class AuditExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sas_dump', [$this, 'dump']),
        ];
    }

    public function dump($variable)
    {
        return VarDumper::dump($variable);
    }
}
