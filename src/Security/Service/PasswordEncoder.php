<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Messenger\Message\EntityPersisted;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PasswordEncoder implements MessageSubscriberInterface
{
    private $service;

    public function __construct(UserPasswordEncoderInterface $service)
    {
        $this->service = $service;
    }

    public function __invoke(EntityPersisted $message): void
    {
        $user = $message->getEntity();
        if (!$user instanceof UserInterface) {
            return;
        }

        if ($plainPassword = $user->getPlainPassword()) {
            $user->setPassword($this->service->encodePassword($user, $plainPassword));
        }
    }

    public static function getHandledMessages(): iterable
    {
        yield EntityPersisted::class;
    }
}
