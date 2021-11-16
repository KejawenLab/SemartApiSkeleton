<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Media;

use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use LogicException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Storage\FileSystemStorage;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Storage extends FileSystemStorage
{
    public function remove($obj, PropertyMapping $mapping): ?bool
    {
        if (!$obj instanceof MediaInterface) {
            parent::upload($obj, $mapping);
        }

        $name = $mapping->getFileName($obj);
        if (empty($name)) {
            return false;
        }

        return $this->doRemove($mapping, $this->getUploadDir($obj, $mapping), $name);
    }

    public function upload($obj, PropertyMapping $mapping): void
    {
        if (!$obj instanceof MediaInterface) {
            parent::upload($obj, $mapping);
        }

        $file = $mapping->getFile($obj);
        if (!($file instanceof UploadedFile)) {
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
            false !== $dimensions = getimagesize($file->getRealPath())
        ) {
            $mapping->writeProperty($obj, 'dimensions', array_splice($dimensions, 0, 2));
        }

        $dir = $this->getUploadDir($obj, $mapping);
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
        if (!$name) {
            return null;
        }

        if (!$obj->isPublic()) {
            return sprintf('%s/%s%s', $mapping->getUriPrefix(), $obj->getFolder() ? sprintf('%s/', $obj->getFolder()) : '', $name);
        }

        return sprintf('%s/%s/%s%s', $mapping->getUriPrefix(), MediaInterface::PUBLIC_FIELD, $obj->getFolder() ? sprintf('%s/', $obj->getFolder()) : '', $name);
    }

    private function getUploadDir(MediaInterface $media, PropertyMapping $mapping): string
    {
        $target = null;
        if (null !== $media->getFolder()) {
            foreach (explode('/', $media->getFolder()) as $value) {
                if ('' !== $value) {
                    $target = sprintf('%s%s%s', $target, $value, DIRECTORY_SEPARATOR);
                }
            }
        }

        return trim(
            sprintf(
                '%s%s%s',
                $mapping->getUploadDir($media),
                DIRECTORY_SEPARATOR,
                rtrim($target, DIRECTORY_SEPARATOR)
            ),
            DIRECTORY_SEPARATOR
        );
    }
}
