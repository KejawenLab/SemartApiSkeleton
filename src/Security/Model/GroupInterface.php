<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface GroupInterface extends PermissionableInterface
{
    public function getId(): ?string;

    public function getCode(): ?string;

    public function getName(): ?string;
}
