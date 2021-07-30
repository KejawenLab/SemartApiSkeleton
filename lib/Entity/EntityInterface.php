<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Entity;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface EntityInterface
{
    public function getId(): ?string;

    public function getNullOrString(): ?string;
}
