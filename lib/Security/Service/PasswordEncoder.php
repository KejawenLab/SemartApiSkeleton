<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Entity\Message\EntityPersisted;
use KejawenLab\ApiSkeleton\Entity\PasswordHistory;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PasswordEncoder implements MessageSubscriberInterface
{
    public function __construct(
        private UserPasswordEncoderInterface $service,
        private PasswordHistoryService $history
    )
    {
    }

    public function __invoke(EntityPersisted $message): void
    {
        $user = $message->getEntity();
        if (!$user instanceof UserInterface) {
            return;
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

    public static function getHandledMessages(): iterable
    {
        yield EntityPersisted::class;
    }
}
