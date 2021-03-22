<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Util;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class TwigExtension extends AbstractExtension
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('semart_print', [$this, 'toString']),
        ];
    }

    public function toString($data): string
    {
        if ($data instanceof EntityInterface) {
            return (string) $data->getNullOrString();
        } else {
            return $data;
        }
    }
}
