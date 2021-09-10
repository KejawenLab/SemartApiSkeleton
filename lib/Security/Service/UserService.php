<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use Iterator;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Message\PasswordHistory;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\User;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Message\EntityPersisted;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Swoole\Coroutine;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UserService extends AbstractService implements ServiceInterface, MessageSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        UserRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private UserPasswordHasherInterface $passwordHasher,
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
            $mediaService = $this->mediaService;
            Coroutine::create(function () use ($mediaService, $user): void {
                $media = new Media();
                $media->setFolder(UserInterface::PROFILE_MEDIA_FOLDER);
                $media->setHidden(true);
                $media->setPublic(false);
                $media->setFile($user->getFile());

                $mediaService->save($media);

                $user->setProfileImage($media->getFileName());
            });
        }

        $plainPassword = $user->getPlainPassword();
        if (null != $plainPassword) {
            $holder = new User($user);

            $password = $this->passwordHasher->hashPassword($holder, $plainPassword);
            $user->setPassword($password);

            $this->messageBus->dispatch(new PasswordHistory($holder, $password));
        }
    }

    public function getByDeviceId(string $deviceId): ?UserInterface
    {
        return $this->repository->findByDeviceId($deviceId);
    }

    /**
     * @return Iterator<string>
     */
    public static function getHandledMessages(): iterable
    {
        yield EntityPersisted::class;
    }
}
