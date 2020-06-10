<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Messenger;

use Alpabit\ApiSkeleton\Messenger\Message\EntityMessage;
use Alpabit\ApiSkeleton\Messenger\Message\EntityPersisted;
use Alpabit\ApiSkeleton\Messenger\Message\EntityRemoved;
use Alpabit\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class EntityMessageHandler implements MessageSubscriberInterface
{
    private $registry;

    private $messageBus;

    public function __construct(ManagerRegistry $registry, MessageBusInterface $messageBus)
    {
        $this->registry = $registry;
        $this->messageBus = $messageBus;
    }

    public function __invoke(EntityMessage $message): void
    {
        $class = get_class($message->getData());
        /** @var EntityManagerInterface $manager */
        $manager = $this->registry->getManagerForClass($class);
        if (!$manager) {
            throw new EntityNotFoundException();
        }

        /** @var ServiceableRepositoryInterface $repository */
        $repository = $manager->getRepository($class);
        if (EntityMessage::ACTION_PERSIST === $message->getAction()) {
            $this->messageBus->dispatch(new EntityPersisted($message->getData()));
            $repository->persist($message->getData());
        }

        if (EntityMessage::ACTION_REMOVE === $message->getAction()) {
            $this->messageBus->dispatch(new EntityRemoved($message->getData()));
            $repository->remove($message->getData());
        }
    }

    public static function getHandledMessages(): iterable
    {
        yield EntityMessage::class;
    }
}
