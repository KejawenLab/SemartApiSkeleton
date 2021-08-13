<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Entity\Message\EntityPersisted;
use KejawenLab\ApiSkeleton\Entity\PasswordHistory;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\User;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UserService extends AbstractService implements ServiceInterface, MessageSubscriberInterface
{
    public function __construct(
        MessageBusInterface $messageBus,
        UserRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private UserPasswordEncoderInterface $service,
        private PasswordHistoryService $history,
        private MediaService $mediaService,
        private StorageInterface $storage,
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function __invoke(EntityPersisted $message): void
    {
        $user = $message->getEntity();
        if (!$user instanceof UserInterface) {
            return;
        }

        if ($user->getFile()) {
            $media = new Media();
            $media->setFolder(UserInterface::PROFILE_MEDIA_FOLDER);
            $media->setHidden(true);
            $media->setPublic(false);
            $media->setFile($user->getFile());

            $this->mediaService->save($media);

            $user->setProfileImage(sprintf('%s/%s', UserInterface::PROFILE_MEDIA_FOLDER, $this->storage->resolveUri($media, MediaInterface::FILE_FIELD)));
        }

        if ($plainPassword = $user->getPlainPassword()) {
            $password = $this->service->encodePassword(new User($user), $plainPassword);
            $user->setPassword($password);

            $passwordHistory = new PasswordHistory();
            $passwordHistory->setSource($user::class);
            $passwordHistory->setIdentifier($user->getId());
            $passwordHistory->setPassword($password);

            $this->history->save($passwordHistory);
        }
    }

    public function getByDeviceId(string $deviceId): ?UserInterface
    {
        return $this->repository->findOneBy(['deviceId' => $deviceId]);
    }

    public static function getHandledMessages(): iterable
    {
        yield EntityPersisted::class;
    }
}
