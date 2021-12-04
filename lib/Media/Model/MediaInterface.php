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
    final public const FILE_FIELD = 'file';

    final public const PUBLIC_FIELD = 'public';

    public function getFile(): ?File;

    public function getFileName(): ?string;

    public function getFolder(): ?string;

    public function getFileUrl(): ?string;

    public function isPublic(): bool;

    public function isHidden(): bool;
}
