<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Media;

use LogicException;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Storage\FileSystemStorage;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Storage extends FileSystemStorage
{
    public function upload($obj, PropertyMapping $mapping): void
    {
        if (!$obj instanceof MediaInterface) {
            parent::upload($obj, $mapping);
        }

        $file = $mapping->getFile($obj);
        if (null === $file || !($file instanceof UploadedFile)) {
            throw new LogicException('No uploadable file found');
        }

        $name = $mapping->getUploadName($obj);
        $mapping->setFileName($obj, $name);
        $mapping->writeProperty($obj, 'size', $file->getSize());
        $mapping->writeProperty($obj, 'mimeType', $file->getMimeType());
        $mapping->writeProperty($obj, 'originalName', $file->getClientOriginalName());

        if (
            str_contains($file->getMimeType(), 'image/') &&
            'image/svg+xml' !== $file->getMimeType() &&
            false !== $dimensions = @getimagesize($file->getRealPath())
        ) {
            $mapping->writeProperty($obj, 'dimensions', array_splice($dimensions, 0, 2));
        }

        $target = null;
        if (null !== $obj->getFolder()) {
            foreach (explode('/', $obj->getFolder()) as $value) {
                if ($value) {
                    $target = sprintf('%s%s%s', $target, $value, DIRECTORY_SEPARATOR);
                }
            }
        }

        $target = rtrim($target, DIRECTORY_SEPARATOR);
        $dir = trim(sprintf('%s%s%s', $mapping->getUploadDir($obj), DIRECTORY_SEPARATOR, $target), DIRECTORY_SEPARATOR);
        $fileSystem = new Filesystem();
        $storage = sprintf('%s%s%s', $mapping->getUploadDestination(), DIRECTORY_SEPARATOR, $dir);
        if (!$fileSystem->exists($storage)) {
            $fileSystem->mkdir($storage);
        }

        $this->doUpload($mapping, $file, $dir, $name);
    }

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
            $uploadDir = sprintf('%s/%s', MediaInterface::PUBLIC_FIELD, $uploadDir);
        }

        return sprintf('%s/%s%s%s',
            $mapping->getUriPrefix(),
            $uploadDir,
            $obj->getFolder() ? sprintf('%s/', $obj->getFolder()) : '',
            $name
        );
    }

    private function convertWindowsDirectorySeparator(string $string): string
    {
        return str_replace('\\', '/', $string);
    }
}
