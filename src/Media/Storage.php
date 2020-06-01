<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Media;

use Alpabit\ApiSkeleton\Media\Model\MediaInterface;
use Vich\UploaderBundle\Storage\FileSystemStorage;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Storage extends FileSystemStorage
{
    public function resolveUri($obj, ?string $fieldName = null, ?string $className = null): ?string
    {
        if (!$obj instanceof MediaInterface) {
            return parent::resolveUri($obj, $fieldName, $className);
        }

        [$mapping, $name] = $this->getFilename($obj, $fieldName, $className);
        if (empty($name)) {
            return null;
        }

        $uploadDir = $this->convertWindowsDirectorySeparator($mapping->getUploadDir($obj));
        $uploadDir = empty($uploadDir) ? '' : $uploadDir;
        if ($obj->isPublic()) {
            $uploadDir = sprintf('public/%s', $uploadDir);
        }

        return sprintf('%s/%s%s', $mapping->getUriPrefix(), $uploadDir, $name);
    }

    private function convertWindowsDirectorySeparator(string $string): string
    {
        return str_replace('\\', '/', $string);
    }
}
