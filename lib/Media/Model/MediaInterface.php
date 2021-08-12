<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Media\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface MediaInterface extends EntityInterface
{
    public const FILE_FIELD = 'file';

    public const PUBLIC_FIELD = 'public';

    public function getFile(): ?File;

    public function getFileName(): ?string;

    public function getFolder(): ?string;

    public function getFileUrl(): ?string;

    public function isPublic(): bool;

    public function isHidden(): bool;
}
