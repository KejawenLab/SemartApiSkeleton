<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Entity\Message\EntityPersisted;
use Alpabit\ApiSkeleton\Entity\PasswordHistory;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PasswordEncoder implements MessageSubscriberInterface
{
    private UserPasswordEncoderInterface $service;

    private PasswordHistoryService $history;

    public function __construct(UserPasswordEncoderInterface $service, PasswordHistoryService $history)
    {
        $this->service = $service;
        $this->history = $history;
    }

    public function __invoke(EntityPersisted $message): void
    {
        $user = $message->getEntity();
        if (!$user instanceof UserInterface) {
            return;
        }

        if ($plainPassword = $user->getPlainPassword()) {
            $password = $this->service->encodePassword($user, $plainPassword);
            $user->setPassword($password);

            $passwordHistory = new PasswordHistory();
            $passwordHistory->setSource(get_class($user));
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
