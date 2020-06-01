<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Media\Model;

use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface MediaInterface
{
    public const FILE_FIELD = 'file';

    public function getFile(): ?File;

    public function getFilePath(): ?string;

    public function getFileUrl(): ?string;

    public function isPublic(): bool;
}
