<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Media;

use Alpabit\ApiSkeleton\Media\Model\MediaInterface;
use Alpabit\ApiSkeleton\Media\Model\MediaRepositoryInterface;
use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
*/
final class MediaService extends AbstractService implements ServiceInterface
{
    private $storage;

    public function __construct(StorageInterface $storage, MessageBusInterface $messageBus, MediaRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        $this->storage = $storage;

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
