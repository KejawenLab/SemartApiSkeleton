<?php

declare(strict_types=1);

namespace App\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface GroupInterface
{
    public function getId(): ?string;

    public function getCode(): ?string;

    public function getName(): ?string;
}
