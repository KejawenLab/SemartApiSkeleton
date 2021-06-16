<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Media;

use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Media\Model\MediaRepositoryInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class MediaService extends AbstractService implements ServiceInterface
{
    public function __construct(
        private StorageInterface $storage,
        MessageBusInterface $messageBus,
        MediaRepositoryInterface $repository,
        AliasHelper $aliasHelper
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function get(string $id)
    {
        /** @var MediaInterface $media */
        $media = parent::get($id);
        if ($media && !$media->getFileUrl()) {
            $media->setFileUrl($this->storage->resolveUri($media, MediaInterface::FILE_FIELD));
        }

        return $media;
    }

    public function getByFile(string $fileName): ?MediaInterface
    {
        $file = explode('/', $fileName);
        $fileName = array_pop($file);
        $folder = implode('/', $file);

        return $this->repository->findOneBy(['fileName' => $fileName, 'folder' => $folder]);
    }
}
