<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Media;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Storage\AbstractStorage as Base;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractStorage extends Base
{
    #[\Override]
    public function resolveUri(object|array $obj, ?string $fieldName = null, ?string $className = null): ?string
    {
        [$mapping, $name] = $this->getFilename($obj, $fieldName, $className);

        if (empty($name)) {
            return null;
        }

        $uploadDir = $this->convertWindowsDirectorySeparator($mapping->getUploadDir($obj));
        $uploadDir = $uploadDir === '' ? '' : $uploadDir . '/';

        return \sprintf('%s/%s', $mapping->getUriPrefix(), $uploadDir . $name);
    }

    private function convertWindowsDirectorySeparator(string $string): string
    {
        return \str_replace('\\', '/', $string);
    }

    protected function doUpload(PropertyMapping $mapping, File $file, ?string $dir, string $name): ?File
    {
        $uploadDir = $mapping->getUploadDestination() . \DIRECTORY_SEPARATOR . $dir;

        if (!\file_exists($uploadDir)) {
            if (!\mkdir($uploadDir, recursive: true)) {
                throw new \Exception('Could not create directory "' . $uploadDir . '"');
            }
        }
        if (!\is_dir($uploadDir)) {
            throw new \Exception('Tried to move file to directory "' . $uploadDir . '" but it is a file');
        }

        if ($file instanceof UploadedFile) {
            return $file->move($uploadDir, $name);
        }
        $targetPathname = $uploadDir . \DIRECTORY_SEPARATOR . $name;
        if (!\copy($file->getPathname(), $targetPathname)) {
            throw new \RuntimeException('Could not copy file');
        }

        return new File($targetPathname);
    }

    protected function doRemove(PropertyMapping $mapping, ?string $dir, string $name): ?bool
    {
        $file = $this->doResolvePath($mapping, $dir, $name);

        if (!\file_exists($file) || !\unlink($file)) {
            throw new \Exception('Cannot remove file ' . $file);
        }

        return true;
    }

    protected function doResolvePath(
        PropertyMapping $mapping,
        ?string         $dir,
        string          $name,
        ?bool           $relative = false
    ): string
    {
        $path = $dir !== null && $dir !== '' && $dir !== '0' ? $dir . \DIRECTORY_SEPARATOR . $name : $name;

        if ($relative === true) {
            return $path;
        }

        return $mapping->getUploadDestination() . \DIRECTORY_SEPARATOR . $path;
    }
}
