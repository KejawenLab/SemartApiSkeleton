<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Test\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface TestInterface
{
    public function getId(): ?string;

    public function getCode(): ?string;

    public function getName(): ?string;
}
