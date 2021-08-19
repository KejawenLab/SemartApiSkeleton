<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use Iterator;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Entity\Message\EntityPersisted;
use KejawenLab\ApiSkeleton\Entity\PasswordHistory;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\User;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UserService extends AbstractService implements ServiceInterface, MessageSubscriberInterface
{
    public function __construct(
        MessageBusInterface $messageBus,
        UserRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private UserPasswordHasherInterface $service,
        private PasswordHistoryService $history,
        private MediaService $mediaService,
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function __invoke(EntityPersisted $message): void
    {
        $user = $message->getEntity();
        if (!$user instanceof UserInterface) {
            return;
        }

        if (null !== $user->getFile()) {
            $media = new Media();
            $media->setFolder(UserInterface::PROFILE_MEDIA_FOLDER);
            $media->setHidden(true);
            $media->setPublic(false);
            $media->setFile($user->getFile());

            $this->mediaService->save($media);

            $user->setProfileImage($media->getFileName());
        }

        if ($plainPassword = $user->getPlainPassword()) {
            $password = $this->service->hashPassword(new User($user), $plainPassword);
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

    /**
     * @return Iterator<string>
     */
    public static function getHandledMessages(): iterable
    {
        yield EntityPersisted::class;
    }
}
