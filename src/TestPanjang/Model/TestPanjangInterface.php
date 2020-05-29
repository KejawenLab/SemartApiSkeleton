<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\TestPanjang\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface TestPanjangInterface
{
    public function getId(): ?string;

    public function getCode(): ?string;

    public function getName(): ?string;
}
